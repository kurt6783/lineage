<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasureDistributions extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'treasure_id',
        'user_id',
        'product',
        'player_name',
        'status'
    ];

    public function treasure()
    {
        return $this->belongsTo(Treasure::class, 'treasure_id');
    }
}
