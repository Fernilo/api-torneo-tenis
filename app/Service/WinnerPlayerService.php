<?php 
namespace App\Service;

use App\Enums\TournamentTypeEnum;
use App\Models\Player;

class WinnerPlayerService 
{
    public function winnerPlayer(array $match , int $tournamentType)
    {
        $player1 = Player::find($match[0]);
        $player2 = Player::find($match[1]);
       
        $value1 = $player1->skill + $player1->good_look;
        $value2 = $player2->skill + $player2->good_look;
        if($tournamentType === TournamentTypeEnum::MALE) {
            $value1 = $value1 + $player1->travel_speed + $player1->strengh;
            $value2 = $value2 + $player2->travel_speed + $player2->strengh;
        } else {
            $value1 = $value1 - $player1->reaction_time;
            $value2 = $value2 - $player2->reaction_time;
        }

        return ($value1 > $value2)? $player1->id : $player2->id;
     
    }
}