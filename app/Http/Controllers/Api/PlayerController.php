<?php

namespace App\Http\Controllers\Api;

use App\Events\DeletedPlayer;
use App\Exceptions\PlayerNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Tournament;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $players = Player::all();

            if(!$players->count()){
                throw new PlayerNotFoundException("Sorry! There ared not players registered yet.");
            }

            return response()->json(
                $players,
                200
            );
        }catch(Exception $e){
            return response()->json(
                ["message" => $e->getMessage()],
                404
            );
        }catch(PlayerNotFoundException $e){
            return response()->json(
                ["message" => $e->getMessage()],
                404
            );
        }
    }

    public function pdf($id) 
    {
        $tournament = Tournament::find($id);
     
        $players1 = Player::join('matches', function ($join) use($id){
            $join->on('players.id', '=', 'matches.player_1_id')
                 ->where('matches.tournament_id', '=', $id);
        })
        ->select('players.*');

        $playersTotal = Player::join('matches', function ($join) use($id){
            $join->on('players.id', '=', 'matches.player_2_id')
                 ->where('matches.tournament_id', '=', $id);
        })
        ->union($players1)
        ->select('players.*')
        ->get();

        $cacheKey = 'players.pdf';
        $minutes = 3600; // Tiempo en minutos para almacenar en caché

        // Intentar recuperar el PDF de la caché
        $pdf = Cache::remember($cacheKey, $minutes, function () use($playersTotal,$tournament){
            // Lógica para generar el PDF
            $data = [
                'playersTotal' => $playersTotal,
                'tournament' => $tournament
            ];
            return PDF::loadView('players.pdf', $data)->output();
        });

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Cache-Control', 'private, max-age=600, must-revalidate');


        // $pdf = Pdf::loadView('players.pdf', ['playersTotal' => $playersTotal,'tournament' => $tournament]);
  
        // return $pdf->stream();

        // return view('players.pdf',compact('playersTotal','tournament'));
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
            Player::create($request->all());

            return response()->json(
                ["message" => "Success! The player was registered."],
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
            $player = Player::withCount('tournaments')->findOrFail($id)->toArray();

            return response()->json(
                $player,
                200
            );
        }catch(ModelNotFoundException $e){
            return response()->json(
                ["message" => "Sorry! We couldn't find the player you're looking for."],
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $player = Player::findOrFail($id);
            
            $player->delete();
            
            DeletedPlayer::dispatch($player);
          
            return response()->json(
                ['message' => 'Success! The player was deleted and replaced with another.'],
                200
            );
        }catch(Exception $e){
            return response()->json(
                ["message" => $e->getMessage()],
                404
            );
        }
    }
}
