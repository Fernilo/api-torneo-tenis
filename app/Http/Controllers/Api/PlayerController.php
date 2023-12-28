<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PlayerNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Player;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
