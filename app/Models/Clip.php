<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clip extends Model
{
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
