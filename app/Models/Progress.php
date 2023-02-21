<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'backup_id',
        'backup_type',
        'completed',
        'log',
        'created_by',
    ];
}