<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Template information
            $table->longText('system_prompt_template')->nullable(); // Original system prompt template
            $table->longText('user_prompt_template')->nullable(); // Original user prompt template
            $table->longText('populated_user_prompt')->nullable(); // Final prompt sent to AI with variables replaced
            
            // Request data
            $table->json('form_data'); // All form inputs as JSON
            $table->json('exports_data')->nullable(); // Export table data
            $table->json('competitors_data')->nullable(); // Competitors table data
            
            // API details
            $table->string('api_model')->default('gpt-4-0613'); // OpenAI model used
            $table->decimal('api_temperature', 3, 2)->default(0.30); // Temperature setting
            
            // Response data
            $table->longText('api_response')->nullable(); // Full AI response text
            $table->json('api_response_raw')->nullable(); // Raw JSON response from OpenAI
            $table->boolean('success')->default(false); // Whether request was successful
            $table->text('error_message')->nullable(); // Error message if failed
            
            // Metadata
            $table->integer('response_time_ms')->nullable(); // Response time in milliseconds
            $table->integer('tokens_used')->nullable(); // Tokens consumed (if available)
            $table->decimal('cost_estimate', 8, 4)->nullable(); // Estimated cost in USD
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'created_at']);
            $table->index('success');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_requests');
    }
};