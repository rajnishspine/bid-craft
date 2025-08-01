<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'system_prompt_template',
        'user_prompt_template',
        'populated_user_prompt',
        'form_data',
        'exports_data',
        'competitors_data',
        'api_model',
        'api_temperature',
        'api_response',
        'api_response_raw',
        'success',
        'error_message',
        'response_time_ms',
        'tokens_used',
        'cost_estimate',
    ];

    protected $casts = [
        'form_data' => 'array',
        'exports_data' => 'array',
        'competitors_data' => 'array',
        'api_response_raw' => 'array',
        'success' => 'boolean',
        'api_temperature' => 'decimal:2',
        'cost_estimate' => 'decimal:4',
    ];

    /**
     * Get the user that made this AI request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template used for this AI request
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get all template versions used in this request
     */
    public function templateVersions(): HasMany
    {
        return $this->hasMany(TemplateVersion::class);
    }

    /**
     * Get the system prompt template version
     */
    public function systemTemplate(): ?TemplateVersion
    {
        return $this->templateVersions()
            ->where('template_type', 'system')
            ->first();
    }

    /**
     * Get the user prompt template version
     */
    public function userTemplate(): ?TemplateVersion
    {
        return $this->templateVersions()
            ->where('template_type', 'user')
            ->first();
    }

    /**
     * Calculate estimated cost based on tokens (rough estimate)
     */
    public function calculateEstimatedCost(): float
    {
        if (!$this->tokens_used) {
            return 0.0;
        }

        // GPT-4 pricing (approximate): $0.03 per 1K prompt tokens, $0.06 per 1K completion tokens
        // We'll use an average of $0.045 per 1K tokens as a rough estimate
        return ($this->tokens_used / 1000) * 0.045;
    }

    /**
     * Get formatted response time
     */
    public function getFormattedResponseTime(): string
    {
        if (!$this->response_time_ms) {
            return 'N/A';
        }

        if ($this->response_time_ms < 1000) {
            return $this->response_time_ms . 'ms';
        }

        return round($this->response_time_ms / 1000, 2) . 's';
    }

    /**
     * Get success rate for a user
     */
    public static function getSuccessRateForUser(int $userId): float
    {
        $total = static::where('user_id', $userId)->count();
        
        if ($total === 0) {
            return 0.0;
        }

        $successful = static::where('user_id', $userId)
            ->where('success', true)
            ->count();

        return round(($successful / $total) * 100, 2);
    }

    /**
     * Scope for successful requests
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope for failed requests
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }
}