<?php

namespace App\Jobs;

use App\Mail\FinishedTournamentMailable;
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
        Log::info('Iniciando FinishedTournamentEmail job', [
            'job_id' => $this->job->getJobId(),
            'attempt' => $this->attempts()
        ]);

        try {
            $finishedTournamentsService = new FinishedTournamentService();
            $finishedTournaments = $finishedTournamentsService->getFinishedTournament();
            Log::info('Torneos encontrados', [
                'count' => $finishedTournaments->count()
            ]);
            
            $adminEmails = $finishedTournamentsService->getAdminEmails();
            Log::info('Emails de admin encontrados', [
                'count' => count($adminEmails)
            ]);
 
            if(count($finishedTournaments) > 0 && count($adminEmails) > 0) {
                $finishedTournaments->each(function ($tournament) use($adminEmails){
                        $email = new FinishedTournamentMailable($tournament);
                
                        Mail::to($adminEmails)->send($email);
                        Log::info("mail enviado correctamente");
                    }
                );
                
            }
        } catch (\Throwable $th) {
            Log::error('Error en job', [
                'error' => $th->getMessage(),
                'line' => $th->getLine()
            ]);
            throw $th;
        }
    }
}
