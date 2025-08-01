<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's template count from the uploads directory.
     *
     * @return int
     */
    public function getTemplateCountAttribute(): int
    {
        return count(glob(public_path('uploads/*.txt')));
    }

    /**
     * Get all AI requests made by this user
     */
    public function aiRequests()
    {
        return $this->hasMany(AiRequest::class);
    }

    /**
     * Get successful AI requests for this user
     */
    public function successfulAiRequests()
    {
        return $this->hasMany(AiRequest::class)->where('success', true);
    }

    /**
     * Get AI request success rate for this user
     */
    public function getAiSuccessRateAttribute(): float
    {
        return AiRequest::getSuccessRateForUser($this->id);
    }

    /**
     * Get total tokens used by this user
     */
    public function getTotalTokensUsedAttribute(): int
    {
        return $this->aiRequests()->sum('tokens_used') ?? 0;
    }

    /**
     * Get total estimated cost for this user
     */
    public function getTotalCostEstimateAttribute(): float
    {
        return $this->aiRequests()->sum('cost_estimate') ?? 0.0;
    }
}