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
    {
        $idsArray = [];
        $count = 1;
        do {
            $idPlayer1 = Player::getRandomPlayerId();
            $idPlayer2 = Player::getRandomPlayerId();
            if($idPlayer1 != $idPlayer2 && !isset($idsArray[$idPlayer1]) && !isset($idsArray[$idPlayer2])) {
                $match = new Matches();
                $idsArray[] = $idPlayer1;
                $idsArray[] = $idPlayer2;
                
                $match->fill([
                    'player_1_id' => $idPlayer1,
                    'player_2_id' => $idPlayer2,
                    'tournament_id' => $event->tournament->id,
                    'match_day' => now()->addDay(),
                ]);
        
                $match->save();
                $count++;
            }
        } while ($count <= ($event->tournament->total_player/2));
        
    }
}
