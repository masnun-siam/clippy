<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
 */
	class Clip extends \Eloquent {}
}

