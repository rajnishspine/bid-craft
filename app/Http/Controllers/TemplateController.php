<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view templates')->only(['index', 'show']);
        $this->middleware('permission:create templates')->only(['create', 'store']);
        $this->middleware('permission:edit templates')->only(['edit', 'update', 'setDefault']);
        $this->middleware('permission:delete templates')->only(['destroy']);
    }

    /**
     * Display a listing of templates
     */
    public function index()
    {
        $templates = Template::orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:templates,name',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
            'is_default' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $template = Template::create([
                    'name' => $request->name,
                    'content' => $request->content,
                    'status' => $request->status,
                    'is_default' => $request->boolean('is_default'),
                ]);

                Log::info('Template created', [
                    'template_id' => $template->id,
                    'template_name' => $template->name,
                    'is_default' => $template->is_default,
                    'user_id' => auth()->id(),
                ]);
            });

            return redirect()->route('templates.index')
                ->with('success', 'Template created successfully!');

        } catch (\Exception $e) {
            Log::error('Template creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to create template. Please try again.');
        }
    }

    /**
     * Show the form for editing a template
     */
    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:templates,name,' . $template->id,
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
            'is_default' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $template) {
                $template->update([
                    'name' => $request->name,
                    'content' => $request->content,
                    'status' => $request->status,
                    'is_default' => $request->boolean('is_default'),
                ]);

                Log::info('Template updated', [
                    'template_id' => $template->id,
                    'template_name' => $template->name,
                    'is_default' => $template->is_default,
                    'user_id' => auth()->id(),
                ]);
            });

            return redirect()->route('templates.index')
                ->with('success', 'Template updated successfully!');

        } catch (\Exception $e) {
            Log::error('Template update failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update template. Please try again.');
        }
    }

    /**
     * Set a template as the default
     */
    public function setDefault(Template $template)
    {
        try {
            $template->setAsDefault();

            Log::info('Template set as default', [
                'template_id' => $template->id,
                'template_name' => $template->name,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Template '{$template->name}' has been set as default."
            ]);

        } catch (\Exception $e) {
            Log::error('Set default template failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to set template as default. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified template
     */
    public function destroy(Template $template)
    {
        try {
            // Check if this is the default template
            if ($template->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the default template. Please set another template as default first.'
                ], 400);
            }

            // Check if template is being used in AI requests
            $requestCount = $template->aiRequests()->count();
            if ($requestCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete template. It has been used in {$requestCount} AI request(s)."
                ], 400);
            }

            $templateName = $template->name;
            $template->delete();

            Log::info('Template deleted', [
                'template_name' => $templateName,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Template '{$templateName}' has been deleted successfully."
            ]);

        } catch (\Exception $e) {
            Log::error('Template deletion failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template. Please try again.'
            ], 500);
        }
    }

    /**
     * Get template variables (for preview/help)
     */
    public function getVariables(Template $template)
    {
        try {
            $variables = $template->getVariables();

            return response()->json([
                'success' => true,
                'variables' => $variables,
                'template_name' => $template->name
            ]);

        } catch (\Exception $e) {
            Log::error('Get template variables failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get template variables.'
            ], 500);
        }
    }

    /**
     * Preview template with sample data
     */
    public function preview(Request $request, Template $template)
    {
        try {
            // Sample form data for preview - matches actual form structure
            $sampleData = [
                'product_name' => 'AZITHROMYCIN - 500MG',
                'country' => 'ETHIOPIA',
                'authority' => 'Ministry of Health',
                'tender_quantity' => '100',
                'ib_purchase_price' => '4.50',
                'last_vrl_cif_price' => '5.80',
                'last_year_winning_prize' => '6.25',
                'winner' => 'Previous Winner',
                'last_quoted_year' => '2017',
                'last_quantity' => '100',
                'registration' => 'Registered',
                'grade' => 'EPN',
                'department' => 'IB VRL LATAM',
                'tentative_freight' => '100',
                'client_margin' => '100',
                'client_expenses' => '100',
                'batch_size' => '1000',
                'batch_cost' => '50',
                'local_preference' => '100',
                'foc' => '100',
                'was_winner_local' => 'Yes',
                'supply_remarks' => 'Sample supply remarks',
                'exports_data' => [
                    [
                        'company' => 'Venus',
                        'country' => 'India',
                        'quantity' => '1000',
                        'price' => '100',
                        'competitors' => 'competitors'
                    ],
                    [
                        'company' => 'Venus',
                        'country' => 'India',
                        'quantity' => '2000',
                        'price' => '100',
                        'competitors' => 'Competitors'
                    ]
                ],
                'competitors_data' => [
                    [
                        'manufacturing_company' => 'Venus',
                        'manufacturing_country' => 'India',
                        'marketing_company' => 'Venus',
                        'marketing_country' => 'India',
                        'saudi_agent' => 'TEST',
                        'cif_price' => '100'
                    ],
                    [
                        'manufacturing_company' => 'Venus',
                        'manufacturing_country' => 'India',
                        'marketing_company' => 'Venus',
                        'marketing_country' => 'India',
                        'saudi_agent' => 'Test Agent',
                        'cif_price' => '1200'
                    ]
                ]
            ];

            $populatedContent = $template->populateTemplate($sampleData);

            return response()->json([
                'success' => true,
                'original_content' => $template->content,
                'populated_content' => $populatedContent,
                'sample_data' => $sampleData
            ]);

        } catch (\Exception $e) {
            Log::error('Template preview failed', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate template preview.'
            ], 500);
        }
    }

    /**
     * Get the current default template (API endpoint)
     */
    public function getDefault()
    {
        try {
            $defaultTemplate = Template::getDefault();

            if (!$defaultTemplate) {
                return response()->json([
                    'success' => false,
                    'message' => 'No default template found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'template' => $defaultTemplate
            ]);

        } catch (\Exception $e) {
            Log::error('Get default template failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get default template.'
            ], 500);
        }
    }
}