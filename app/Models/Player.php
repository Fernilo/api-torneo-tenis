<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    public function matchesPlayer1()
    {
        return $this->hasMany(Matches::class,'player_1_id');
    }

    public function matchesPlayer2()
    {
        return $this->hasMany(Matches::class,'player_2_id');
    }

    public function winner()
    {
        return $this->hasMany(Matches::class,'player_win_id');
    }
}
