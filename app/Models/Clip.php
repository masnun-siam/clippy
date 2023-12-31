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
    /**
     * @param array<int,mixed> $array
     */
    public static function create(array $array): void
    {
    }

    use HasFactory;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $clip;

    /**
     * @var string
     */
    private $url;

    /**
     * @return timestamp
     */
    private $expiredAt;
}
