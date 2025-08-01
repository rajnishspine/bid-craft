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
        Schema::create('template_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_request_id')->constrained()->onDelete('cascade');
            
            // Template identification
            $table->string('template_name'); // e.g., 'system_prompt.txt', 'user_prompt_template.txt'
            $table->string('template_type')->default('user'); // 'system' or 'user'
            
            // Template content
            $table->longText('original_content'); // Content before any modifications
            $table->longText('final_content'); // Content after variable replacement (for user templates)
            
            // Change tracking
            $table->json('variables_replaced')->nullable(); // Variables that were replaced and their values
            $table->integer('variables_count')->default(0); // Number of variables replaced
            
            // File metadata
            $table->string('file_path'); // Path to template file
            $table->bigInteger('file_size')->nullable(); // File size in bytes
            $table->string('file_hash')->nullable(); // MD5 hash for change detection
            
            $table->timestamps();
            
            // Indexes
            $table->index(['ai_request_id', 'template_type']);
            $table->index('template_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_versions');
    }
};