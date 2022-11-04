<?php

namespace App\Console\Commands;

use App\Models\Assets;
use App\Models\User;
use App\Notifications\ServiceDueNotification;
use Illuminate\Console\Command;

class CheckServicesDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:check-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if a service is due and sends out alerts';

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
        $assets = Assets::all();


        foreach ($assets as $asset) {
            $last_service = $asset->services()->orderBy('created_at', 'DESC')->first();

            if (empty($last_service)) {
                continue;
            }

            if ($last_service->days_remaining < 30) {
                // send out notification
                $users = User::where('designation', 'accountant')->get();

                foreach ($users as $user) {
                    $user->notify(new ServiceDueNotification($last_service));
                }
            }
        }
    }
}
