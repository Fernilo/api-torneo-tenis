<?php

namespace App\Http\Controllers\Api;

use App\Events\TournamentCreated;
use App\Http\Controllers\Controller;
use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $tournament = Tournament::create($request->all());
          
            TournamentCreated::dispatch($tournament);
            return response()->json(
                ["message" => "Success! The tournament was registered with matches."],
                201
            );
        }catch(Exception $e){
            return response()->json(
                ["message" => $e->getMessage()],
                404
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $tournament = Tournament::with(['matches' => function($query) {
                $query->where('match_day' , '<=' , Carbon::now()->toDateString())
                    ->where('resultado' , '!=' , null);
            }, 'matches.player2','matches.player1','matches.winner'])
            ->findOrFail($id);
     
            return response()->json(
                $tournament,
                201
            );
        }catch(Exception $e){
            return response()->json(
                ["message" => $e->getMessage()],
                404
            );
        }
    }

    public function simulateTournament(Request $request)
    {
        try{
       
            $tournament = Tournament::create($request->all());
            $players = Player::inRandomOrder()->pluck('id')->take($request->total_player)->toArray();
            $matches = array_chunk($players,2);

            $winIds = [];
            foreach ($matches as $match) {
                $matchEntity = new Matches();
                    $matchEntity->fill([
                    'player_1_id' => $match[0],
                    'player_2_id' => $match[1],
                    'tournament_id' => $tournament->id,
                    'match_day' => now(),
                    'player_win_id' => $match[1],
                    //  Cambiar el name al campo por result o ver si es necesario
                    'resultado' => '6-3 6-3'
                ]);

                $winIds [] = $matchEntity->player_win_id;
        
                $matchEntity->save();
            }

            $matches1 = array_chunk($winIds,2);
          
            $winIds1 = [];
            foreach ($matches1 as $match1) {
                $matchEntity = new Matches();
                    $matchEntity->fill([
                    'player_1_id' => $match1[0],
                    'player_2_id' => $match1[1],
                    'tournament_id' => $tournament->id,
                    'match_day' => now(),
                    'player_win_id' => $match1[1],
                    //  Cambiar el name al campo por result o ver si es necesario
                    'resultado' => '6-3 6-3'
                ]);

                $winIds1 [] = $matchEntity->player_win_id;
        
                $matchEntity->save();
            }

            dd($winIds1);
            $idsArray = [];
            $count = 1;
            // do {
            //     $idPlayer1 = Player::getRandomPlayerId();
            //     $idPlayer2 = Player::getRandomPlayerId();
            //     if($idPlayer1 != $idPlayer2 && !isset($idsArray[$idPlayer1]) && !isset($idsArray[$idPlayer2])) {
            //         $match = new Matches();
            //         $idsArray[] = $idPlayer1;
            //         $idsArray[] = $idPlayer2;
                    
            //         $match->fill([
            //             'player_1_id' => $idPlayer1,
            //             'player_2_id' => $idPlayer2,
            //             'tournament_id' => $tournament->id,
            //             'match_day' => now()->addDay(),
            //         ]);
            
            //         $match->save();
            //         $count++;
            //     }
            // } while ($count <= ($tournament->total_player/2));
            
         


          
            // TournamentCreated::dispatch($tournament);
            return response()->json(
                ["message" => "Success! The tournament was registered with matches."],
                201
            );
        }catch(Exception $e){
            return response()->json(
                ["message" => $e->getMessage()],
                404
            );
        }
    }
}
