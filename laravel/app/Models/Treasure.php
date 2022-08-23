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
        'description',
        'accountant_id',
        'sell_at',
    ];

    protected $casts = [
        'players' => 'json',
        'kill_at' => 'datetime:Y-m-d H:i:s',
        'deadline' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'Updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    const status = [
        'CALLING' => 0,
        'SELLING' => 1,
        'SOLD' => 2,
        'ALLOCATING' => 3,
        'FINISH' => 4,
    ];

    const statusTranslate = [
        0 => '登記中',
        1 => '販售中',
        2 => '已售出',
        3 => '分鑽中',
        4 => '結案',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function getSellingPriceAttribute()
    {
        return $this->attributes['selling_price'] ?? '-';
    }

    public function ownerInfo()
    {
        return $this->belongsTo(Player::class, 'owner', 'id');
    }
}
