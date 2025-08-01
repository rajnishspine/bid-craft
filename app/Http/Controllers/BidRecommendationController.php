<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\AiRequest;
use App\Models\Template;
use App\Models\TemplateVersion;

class BidRecommendationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // View bid form requires 'view bid recommendations' permission
        $this->middleware('permission:view bid recommendations')->only(['index']);
        // Using AI requires 'create ai requests' permission  
        $this->middleware('permission:create ai requests')->only(['askAI']);
        // Viewing history requires 'view ai history' permission
        $this->middleware('permission:view ai history')->only(['history']);
    }

    /**
     * Display the bid recommendation dashboard
     * Team Members can view this form to input bid data
     */
    public function index()
    {
        return view('bid-recommendations.index');
    }

    /**
     * Ask AI for bid recommendations
     */
    public function askAI(Request $request)
    {
        $startTime = microtime(true);
        $aiRequestRecord = null;
        
        try {
            // Get all form data
            $data = $request->all();
            
            // Get the default template from database
            $defaultTemplate = Template::getDefault();
            if (!$defaultTemplate) {
                throw new \Exception('No default template found. Please set a default template first.');
            }
            
            // Populate template with form data
            $populatedPrompt = $defaultTemplate->populateTemplate($data);
            
            // Create AI request record in database
            $aiRequestRecord = AiRequest::create([
                'user_id' => Auth::id(),
                'template_id' => $defaultTemplate->id,
                'system_prompt_template' => $defaultTemplate->content, // Store original template
                'user_prompt_template' => $defaultTemplate->content,   // For backward compatibility
                'populated_user_prompt' => $populatedPrompt,
                'form_data' => $this->cleanFormData($data),
                'exports_data' => $data['exports_data'] ?? [],
                'competitors_data' => $data['competitors_data'] ?? [],
                'api_model' => 'gpt-4-0613',
                'api_temperature' => 0.3,
                'success' => false, // Will update after successful API call
            ]);

            // Call OpenAI API
            $apiKey = env('OPENAI_API_KEY');
            if (!$apiKey) {
                throw new \Exception('OpenAI API key not configured');
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-0613',
            'temperature' => 0.3,
            'messages' => [
                    [
                        'role' => 'user',
                        'content' => $populatedPrompt // This contains the complete unified template: system instructions + populated user data
                    ]
                ]
            ]);
            
            $endTime = microtime(true);
            $responseTimeMs = round(($endTime - $startTime) * 1000);
            
            if (!$response->successful()) {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }
            
            $responseData = $response->json();
            $aiResponse = $responseData['choices'][0]['message']['content'] ?? 'No response received';
            $tokensUsed = $responseData['usage']['total_tokens'] ?? null;
            
            // Update AI request record with successful response
            $aiRequestRecord->update([
                'api_response' => $aiResponse,
                'api_response_raw' => $responseData,
                'success' => true,
                'response_time_ms' => $responseTimeMs,
                'tokens_used' => $tokensUsed,
                'cost_estimate' => $tokensUsed ? ($tokensUsed / 1000) * 0.045 : null,
            ]);
            
            Log::info('AI Request Successful', [
                'request_id' => $aiRequestRecord->id,
                'user_id' => Auth::id(),
                'response_time_ms' => $responseTimeMs,
                'tokens_used' => $tokensUsed
            ]);
            
            return response()->json([
                'success' => true,
                'response' => $aiResponse,
                'message' => 'AI analysis completed successfully',
                'request_id' => $aiRequestRecord->id,
                'tokens_used' => $tokensUsed,
                'response_time' => $responseTimeMs . 'ms'
            ]);

        } catch (\Exception $e) {
            $endTime = microtime(true);
            $responseTimeMs = round(($endTime - $startTime) * 1000);
            
            // Update AI request record with error
            if ($aiRequestRecord) {
                $aiRequestRecord->update([
                    'success' => false,
                    'error_message' => $e->getMessage(),
                    'response_time_ms' => $responseTimeMs,
                ]);
            }
            
            Log::error('AI Request Error', [
                'request_id' => $aiRequestRecord?->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'response_time_ms' => $responseTimeMs
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting AI recommendations: ' . $e->getMessage(),
                'request_id' => $aiRequestRecord?->id
            ], 500);
        }
    }



    /**
     * Parse exports data from form
     */
    private function parseExportsData($data)
    {
        $exports = [];
        
        // Look for export data patterns in form data
        foreach ($data as $key => $value) {
            if (strpos($key, 'export_') === 0 && !empty($value)) {
                $exports[] = "  - {$value['company']} → {$value['country']} → {$value['qty']} @ \${$value['price']}";
            }
        }
        
        // If no exports found, return default
        if (empty($exports)) {
            return "  - No export data provided";
        }
        
        return implode("\n", $exports);
    }

    /**
     * Parse competitors data from form
     */
    private function parseCompetitorsData($data)
    {
        $competitors = [];
        
        // Look for competitor data patterns
        foreach ($data as $key => $value) {
            if (strpos($key, 'competitor_') === 0 && !empty($value)) {
                $competitors[] = "  - {$value}";
            }
        }
        
        // If no competitors found, return default
        if (empty($competitors)) {
            return "  - No competitors data provided";
        }
        
        return implode("\n", $competitors);
    }

    /**
     * Clean form data by removing unnecessary fields
     */
    private function cleanFormData($data)
    {
        // Remove internal Laravel fields
        unset($data['_token']);
        
        return $data;
    }

    /**
     * Save template versions used in this AI request
     */
    private function saveTemplateVersions($aiRequest, $systemPrompt, $userPromptTemplate, $populatedUserPrompt)
    {
        // Save system prompt template version
        TemplateVersion::create([
            'ai_request_id' => $aiRequest->id,
            'template_name' => 'system_prompt.txt',
            'template_type' => 'system',
            'original_content' => $systemPrompt,
            'final_content' => $systemPrompt, // System prompt doesn't change
            'variables_replaced' => [],
            'variables_count' => 0,
            'file_path' => public_path('uploads/system_prompt.txt'),
            'file_size' => strlen($systemPrompt),
            'file_hash' => TemplateVersion::generateHash($systemPrompt),
        ]);

        // Save user prompt template version
        $variables = TemplateVersion::extractVariables($userPromptTemplate);
        $variablesReplaced = $this->getReplacedVariables($userPromptTemplate, $populatedUserPrompt);
        
        TemplateVersion::create([
            'ai_request_id' => $aiRequest->id,
            'template_name' => 'user_prompt_template.txt',
            'template_type' => 'user',
            'original_content' => $userPromptTemplate,
            'final_content' => $populatedUserPrompt,
            'variables_replaced' => $variablesReplaced,
            'variables_count' => count($variables),
            'file_path' => public_path('uploads/user_prompt_template.txt'),
            'file_size' => strlen($userPromptTemplate),
            'file_hash' => TemplateVersion::generateHash($userPromptTemplate),
        ]);
    }

    /**
     * Get variables that were replaced and their values
     */
    private function getReplacedVariables($template, $populated)
    {
        $variables = TemplateVersion::extractVariables($template);
        $replaced = [];
        
        foreach ($variables as $variable) {
            $placeholder = "[{$variable}]";
            
            // Find what this variable was replaced with
            preg_match('/\[' . preg_quote($variable) . '\]/', $template, $matches, PREG_OFFSET_CAPTURE);
            if (!empty($matches)) {
                $start = $matches[0][1];
                $length = strlen($placeholder);
                
                // Find the corresponding text in populated template
                $beforeVar = substr($template, 0, $start);
                $afterVar = substr($template, $start + $length);
                
                $beforeIndex = strpos($populated, $beforeVar);
                if ($beforeIndex !== false) {
                    $startIndex = $beforeIndex + strlen($beforeVar);
                    $afterIndex = strpos($populated, $afterVar, $startIndex);
                    
                    if ($afterIndex !== false) {
                        $value = substr($populated, $startIndex, $afterIndex - $startIndex);
                        $replaced[$variable] = $value;
                    }
                }
            }
        }
        
        return $replaced;
    }

    /**
     * Get AI request history for current user with filters and pagination
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = AiRequest::with(['templateVersions', 'user', 'template']);
        
        // Apply status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('success', (bool) $request->status);
        }
        
        // Apply date filter
        if ($request->has('date_filter') && $request->date_filter !== '') {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
            }
        }
        
        // Apply search filter
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereJsonContains('form_data->product_name', $searchTerm)
                  ->orWhereJsonContains('form_data->country', $searchTerm)
                  ->orWhere('api_response', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Get paginated results
        $perPage = $request->get('per_page', 10);
        $history = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Calculate stats (using base query without filters for accurate totals)
        $baseQuery = AiRequest::query();
        $totalRequests = $baseQuery->count();
        $successfulRequests = $baseQuery->where('success', true)->count();
        $successRate = $totalRequests > 0 ? round(($successfulRequests / $totalRequests) * 100, 1) : 0;
        
        return response()->json([
            'success' => true,
            'data' => $history,
            'stats' => [
                'total_requests' => $totalRequests,
                'successful_requests' => $successfulRequests,
                'success_rate' => $successRate,
                'total_tokens_used' => (int) AiRequest::sum('tokens_used'),
                'total_cost_estimate' => (float) AiRequest::sum('cost_estimate'),
            ],
            'filters' => [
                'status' => $request->get('status', ''),
                'date_filter' => $request->get('date_filter', ''),
                'search' => $request->get('search', ''),
                'per_page' => $perPage
            ]
        ]);
    }

    /**
     * Debug endpoint to check data structure
     */
    public function debug()
    {
        try {
            $user = Auth::user();
            
            // Check if tables exist by trying to access them
            $aiRequestsCount = \DB::table('ai_requests')->count();
            $templateVersionsCount = \DB::table('template_versions')->count();
            
            // Get sample data
            $sampleRequest = AiRequest::where('user_id', $user->id)->first();
            
            return response()->json([
                'user_id' => $user->id,
                'ai_requests_table_count' => $aiRequestsCount,
                'template_versions_table_count' => $templateVersionsCount,
                'user_requests_count' => AiRequest::where('user_id', $user->id)->count(),
                'sample_request' => $sampleRequest,
                'raw_query_result' => \DB::table('ai_requests')->where('user_id', $user->id)->get(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}