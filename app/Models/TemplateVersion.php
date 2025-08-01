<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_request_id',
        'template_name',
        'template_type',
        'original_content',
        'final_content',
        'variables_replaced',
        'variables_count',
        'file_path',
        'file_size',
        'file_hash',
    ];

    protected $casts = [
        'variables_replaced' => 'array',
        'variables_count' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the AI request this template version belongs to
     */
    public function aiRequest(): BelongsTo
    {
        return $this->belongsTo(AiRequest::class);
    }

    /**
     * Check if this is a system template
     */
    public function isSystemTemplate(): bool
    {
        return $this->template_type === 'system';
    }

    /**
     * Check if this is a user template
     */
    public function isUserTemplate(): bool
    {
        return $this->template_type === 'user';
    }

    /**
     * Get the number of variables that were replaced
     */
    public function getVariablesReplacedCount(): int
    {
        return $this->variables_count ?: 0;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $bytes = $this->file_size;
        
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return round($bytes / 1048576, 2) . ' MB';
        }
    }

    /**
     * Generate MD5 hash of content
     */
    public static function generateHash(string $content): string
    {
        return md5($content);
    }

    /**
     * Check if template content has changed by comparing hashes
     */
    public function hasContentChanged(string $newContent): bool
    {
        return $this->file_hash !== static::generateHash($newContent);
    }

    /**
     * Extract variables from template content
     */
    public static function extractVariables(string $content): array
    {
        preg_match_all('/\[([A-Z_]+)\]/', $content, $matches);
        return $matches[1] ?: [];
    }

    /**
     * Count variables in template
     */
    public static function countVariables(string $content): int
    {
        return count(static::extractVariables($content));
    }
}