<?php

namespace App\Jobs;

use App\Mail\FinishedTournamentMailable;
use App\Models\Tournament;
use App\Service\FinishedTournamentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FinishedTournamentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $finishedTournaments = (new FinishedTournamentService)->getFinishedTournament();
            $adminEmails = (new FinishedTournamentService)->getAdminEmails();
    
            if(count($finishedTournaments) > 0 && count($adminEmails) > 0) {
                $finishedTournaments->each(function ($tournament) use($adminEmails){
                        $email = new FinishedTournamentMailable($tournament);
                
                        Mail::to($adminEmails)->send($email);
                        Log::info("mail enviado correctamente");
                    }
                );
                
            }
        } catch (\Throwable $th) {
           Log::error('El mail no pudo ser enviado corectamente');
        }
    }
}
