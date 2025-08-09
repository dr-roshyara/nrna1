<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartResultAuthorization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:start-authorization';
 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the result authorization process';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
     public function handle()
    {
        $election = Election::current();
        
        if (!$election) {
            $this->error('No current election found');
            return 1;
        }
        
        if (!$election->hasVotingEnded()) {
            $this->error('Voting has not ended yet');
            return 1;
        }
        
        if (!$election->areResultsVerified()) {
            $this->error('Results are not verified yet');
            return 1;
        }
        
        $election->startAuthorization();
        
        $this->info('Authorization process started successfully');
        $this->info("Required authorizers: {$election->required_authorizers}");
        $this->info("Deadline: {$election->authorization_deadline}");
        
        return 0;
    }
}
