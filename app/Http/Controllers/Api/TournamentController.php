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
        
            while(count($matches) > 1) {
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

                $matches = array_chunk($winIds,2);
            }

            if (count($matches) === 1) {
                $player = Player::find($matches[0][rand(0, 1)]);
                $tournament->champion_id = $player->id;
                $tournament->save();
            }
           
            return response()->json(
                $player,
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
