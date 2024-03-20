<?php

namespace App\Http\Controllers\Api;

use App\Enums\TournamentTypeEnum;
use App\Events\TournamentCreated;
use App\Http\Controllers\Controller;
use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use App\Service\WinnerPlayerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class TournamentController extends Controller
{

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
     * Show data about Tournament
     * @OA\Get (
     *     path="/api/tournaments/{id}",
     *     tags={"Tournament show"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Master Miami"),
     *              @OA\Property(property="total_player", type="number", example="16"),
     *              @OA\Property(property="type", type="string", example="1"),
     *              @OA\Property(property="champion_id", type="string", example="45"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Tournaments] #id"),
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        try{
            $tournament = Tournament::with(['matches' => function($query) {
                $query->where('match_day' , '<=' , Carbon::now()->toDateString())
                    ->where('player_win_id' , '!=' , null);
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
            if($request->total_player % 2 != 0) {
                throw new Exception("La cantidad de jugadores debe ser par");
            }

            $tournament = Tournament::create($request->all());
            $players = Player::inRandomOrder()->where('type','=',$request->type)->pluck('id')->take($request->total_player)->toArray();
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
                        'player_win_id' => (new WinnerPlayerService)->winnerPlayer($match , $tournament->type)
                    ]);
    
                    $winIds [] = $matchEntity->player_win_id;
            
                    $matchEntity->save();
                }

                $matches = array_chunk($winIds,2);
            }

            if (count($matches) === 1) {
                // En la final va a ser random
                $player = Player::find($matches[0][rand(0, 1)]);

                $matchEntity = new Matches();
                    $matchEntity->fill([
                    'player_1_id' => $matches[0][0],
                    'player_2_id' => $matches[0][1],
                    'tournament_id' => $tournament->id,
                    'match_day' => now(),
                    'player_win_id' => $player->id
                ]);
    
                $matchEntity->save();
   
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
