<?php

namespace App\Listeners;

use App\Events\TournamentCreated;
use App\Models\Matches;
use App\Models\Player;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class GenerateMatches
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TournametCreated  $event
     * @return void
     */
    public function handle(TournamentCreated $event)
    {dd($event->tournament);
        $match = new Matches();
        $idsAleatorios = Player::inRandomOrder()->take($event->tournament->total_player)->pluck('id')->toArray();
        $items = Arr::random($idsAleatorios, 2);
        $match->player_1_id = $idsAleatorios[0];
        $match->player_2_id = $idsAleatorios[1];
        $match->tournament_id = $event->tournament->id;
        $match->match_day = now();
        $match->save();
        dd($items);
    }
}
