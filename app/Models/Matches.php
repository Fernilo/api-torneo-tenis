<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $guarded = ['id' , 'created_at' , 'updated_at'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function player1()
    {
        return $this->belongsTo(Player::class,'player_1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class,'player_2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Player::class,'winner_id');
    }
}
