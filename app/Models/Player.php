<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id' , 'created_at' , 'updated_at'];

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

    public function tournaments()
    {
        return $this->hasMany(Tournament::class , 'champion_id');
    }

    public static function getRandomPlayerId()
    {
        return Player::inRandomOrder()->take(1)->pluck('id')->first();
    }
}
