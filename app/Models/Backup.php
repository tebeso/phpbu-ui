<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Backup extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'server',
        'filename',
        'size',
        'deleted',
        'file_created_at',
    ];
}