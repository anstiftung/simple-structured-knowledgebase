<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class migrateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:migrate-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates datasets from the old cowiki_users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = DB::select('select * from cowiki_users');

        foreach($users as $user) {
            $new_user = User::make([
                'name' => Str::slug($user->email),
                'email' => $user->email,
                'email_verified_at' => null,
                'created_at' => $user->created,
                'updated_at' => $user->updated,
            ]);

            $new_user->id = $user->uid;

            $new_user->save();
        }
    }
}
