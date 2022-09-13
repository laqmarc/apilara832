<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dice extends Model
{
    use HasFactory;

    protected $fillable = [
        'dice1',
        'dice2',
        'result',
        'user_id'
    ];

    public function player() 
    {
        return $this->belongsTo('App\Models\User');
    }
}
