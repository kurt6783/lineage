<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class TreasureImage extends Model
{
    use HasDateTimeFormatter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'treasure_id',
        'path',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'Updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public $incrementing = true;

    public $timestamps = true;

}
