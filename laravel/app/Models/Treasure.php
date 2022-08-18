<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class Treasure extends Model
{
    use HasDateTimeFormatter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner',
        'product',
        'selling_price',
        'unit_price',
        'title',
        'status',
        'boss_name',
        'deadline',
        'kill_at',
        'description'
    ];

    protected $casts = [
        'kill_at' => 'datetime:Y-m-d H:i:s',
        'deadline' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'Updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $incrementing = true;

    public $timestamps = true;

}
