<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Clip
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $slug
 * @property string $url
 * @property string|null $password
 * @property string|null $expires_at
 * @method static \Illuminate\Database\Eloquent\Builder|Clip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Clip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Clip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Clip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clip whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clip wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clip whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Clip whereUrl($value)
 * @mixin \Eloquent
 */
class Clip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'url',
        'password',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Check if the clip is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    /**
     * Check if the clip is password protected
     */
    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }

    /**
     * Get the full short URL
     */
    public function getShortUrlAttribute(): string
    {
        return url('/' . $this->slug);
    }

    /**
     * Scope for active clips (not expired)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for expired clips
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope for password protected clips
     */
    public function scopePasswordProtected($query)
    {
        return $query->whereNotNull('password');
    }
}
