<?php

namespace App\Http\Controllers\Api;

use App\Events\TournamentCreated;
use App\Http\Controllers\Controller;
use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Arr;

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
            //Ver de ponerlo en el modelo de matches
            TournamentCreated::dispatch($tournament);
            return response()->json(
                ["message" => "Success! The tournament was registered."],
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
        //
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
