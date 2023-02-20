<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'completed',
        'log',
        'created_by',
    ];
}