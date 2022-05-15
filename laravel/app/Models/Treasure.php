<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasure extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product',
        'selling_price',
        'unit_price',
        'status'
    ];

    public function owner()
    {

    }

    public function denominator()
    {
        return $this->hasMany('');
    }
}
