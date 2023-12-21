<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TokenGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate token for specified user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = \App\Models\User::all();
        $user = $this->choice('Select user', $users->pluck('email')->toArray());
        $user = $users->where('email', $user)->first();
        $token = $user->createToken('CLI generated token')->plainTextToken;
        $this->info($token);
    }
}
