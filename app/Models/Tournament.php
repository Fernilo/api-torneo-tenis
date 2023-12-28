<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $guarded = ['id' , 'created_at' , 'updated_at'];

    public function matches()
    {
        return $this->hasMany(Matches::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
