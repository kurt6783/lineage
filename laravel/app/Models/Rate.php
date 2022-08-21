<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'proportion',
    ];

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
