<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'status',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Before saving, if this template is being set as default,
        // unset all other default templates
        static::saving(function ($template) {
            if ($template->is_default) {
                // Unset all other default templates
                static::where('id', '!=', $template->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Scope to get only active templates
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get the default template
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true)->where('status', 'active');
    }

    /**
     * Get the current default template
     */
    public static function getDefault()
    {
        return static::default()->first();
    }

    /**
     * Set this template as the default
     */
    public function setAsDefault()
    {
        DB::transaction(function () {
            // Unset all other defaults
            static::where('is_default', true)->update(['is_default' => false]);
            
            // Set this as default
            $this->update(['is_default' => true, 'status' => 'active']);
        });
    }

    /**
     * Replace template variables with form data
     */
    public function populateTemplate(array $formData)
    {
        $content = $this->content;
        
        // Create mapping from form field names to CAPS template variables
        $variableMapping = $this->getVariableMapping();
        
        foreach ($formData as $key => $value) {
            // Get the CAPS variable name for this key
            $capsKey = $variableMapping[$key] ?? strtoupper($key);
            
            // Handle arrays (like exports_data, competitors_data)
            if (is_array($value)) {
                if ($key === 'exports_data') {
                    $exportsList = '';
                    foreach ($value as $export) {
                        $exportsList .= "- {$export['company']} → {$export['country']} → {$export['quantity']} @ \${$export['price']}\n";
                    }
                    $content = str_replace("[{$capsKey}]", trim($exportsList), $content);
                } elseif ($key === 'competitors_data') {
                    $competitorsList = '';
                    foreach ($value as $competitor) {
                        $competitorsList .= "- {$competitor['manufacturing_company']} ({$competitor['manufacturing_country']}) → {$competitor['marketing_company']} ({$competitor['marketing_country']}) → CIF: \${$competitor['cif_price']}\n";
                    }
                    $content = str_replace("[{$capsKey}]", trim($competitorsList), $content);
                } else {
                    // For other arrays, convert to comma-separated string
                    $content = str_replace("[{$capsKey}]", implode(', ', $value), $content);
                }
            } else {
                // Handle simple key-value pairs
                $content = str_replace("[{$capsKey}]", $value, $content);
            }
        }
        
        return $content;
    }

    /**
     * Get mapping from form field names to CAPS template variables
     */
    private function getVariableMapping(): array
    {
        return [
            'product_name' => 'PRODUCT_NAME',
            'country' => 'COUNTRY',
            'authority' => 'AUTHORITY',
            'tender_quantity' => 'TENDER_QUANTITY',
            'ib_purchase_price' => 'IBPP',
            'last_vrl_cif_price' => 'LAST_CIF',
            'last_year_winning_prize' => 'LAST_PRICE',
            'winner' => 'WINNER',
            'last_quoted_year' => 'YEAR',
            'last_quantity' => 'LAST_QTY',
            'registration' => 'REGISTRATION',
            'tentative_freight' => 'FREIGHT',
            'client_margin' => 'MARGIN',
            'client_expenses' => 'EXPENSES',
            'batch_size' => 'BATCH_SIZE',
            'batch_cost' => 'BATCH_COST',
            'local_preference' => 'LOCAL_PREF',
            'exports_data' => 'EXPORTS_DATA',
            'competitors_data' => 'COMPETITORS_DATA',
            'grade' => 'GRADE',
            'department' => 'DEPARTMENT',
            'foc' => 'FOC',
            'was_winner_local' => 'WAS_WINNER_LOCAL',
            'supply_remarks' => 'SUPPLY_REMARKS',
        ];
    }

    /**
     * Get variables used in this template
     */
    public function getVariables()
    {
        preg_match_all('/\[([^\]]+)\]/', $this->content, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Relationship: AI requests that used this template
     */
    public function aiRequests()
    {
        return $this->hasMany(AiRequest::class);
    }
}