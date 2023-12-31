<?php

namespace App\Listeners;

use App\Events\DeletedPlayer;
use App\Models\Matches;
use App\Models\Player;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Queue\InteractsWithQueue;

class ReplacePlayer
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
     * @param  \App\Events\DeletedPlayer  $event
     * @return void
     */
    public function handle(DeletedPlayer $event)
    {
        $playerMatches = Matches::select(
                'matches.*'
            )
            ->where('player_1_id' , $event->player->id)
            ->orWhere('player_2_id' , $event->player->id)
            ->join('tournaments' , function($join) {
                $join->on('matches.tournament_id' , '=' , 'tournaments.id')
                    ->whereNull('tournaments.champion_id');
            })
            ->get(); 
    
        if($playerMatches->count() > 0) {
            $newPlayer = Player::getRandomPlayerId();
            
            foreach ($playerMatches as $match) {
                if($match->player_1_id === $event->player->id){
                    $match->player_1_id = $newPlayer;
                    $match->save();
                    
                }

                if($match->player_2_id === $event->player->id){
                    $match->player_2_id = $newPlayer;
                    $match->save();
                }
            }
        }
    }
}
