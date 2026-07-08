<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickEvent extends Model
{
    protected $table = 'clicks';

    protected $fillable = [
        'clip_id',
        'ip',
        'user_agent',
        'referer',
        'accept_language',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'device_type',
        'browser',
        'os',
        'passed_password',
        'country',
    ];

    protected $casts = [
        'passed_password' => 'boolean',
    ];

    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }
}
