<?php

namespace App\Console\Commands;
use App\Jobs\PeriodicSynchronizations;
use Illuminate\Console\Command;
use App\Models\User;
class PeriodicSynshronise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Periodic:Synchronise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // return 0;
        // dd("test");
       $ps = new PeriodicSynchronizations();
        $user =User::find(1);
        // dd($user);
       $googleAccount =$user->googleAccounts;
        // var_dump($googleAccount);
        \App\Jobs\SynchronizeGoogleCalendars::dispatch($googleAccount);

         //    dd($ps);
    }
}
