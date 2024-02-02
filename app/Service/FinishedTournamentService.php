<?php 

namespace App\Service;

use App\Models\Tournament;
use App\Models\User;

class FinishedTournamentService 
{
    public function getFinishedTournament() 
    {
        $tournaments = Tournament::with(['matches' , 'player'])
            ->whereNotNull('champion_id')
            ->whereDate('created_at', date('Y-m-d' , strtotime('-1 day')))
            ->get();

        return $tournaments;
    }

    public function getAdminEmails()
    {
        $admins = User::where('role','admin')
            ->get();

        return $admins;
    }
}