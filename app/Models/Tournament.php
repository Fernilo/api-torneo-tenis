<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Tournament extends Model
{
    use HasFactory,LogsActivity;

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
