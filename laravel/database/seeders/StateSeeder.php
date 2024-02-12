<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['title' => 'Entwurf', 'key' => 'draft'],
            ['title' => 'Review', 'key' => 'review'],
            ['title' => 'Veröffentlicht', 'key' => 'publish']
        ];
        foreach ($states as $state) {
            State::create($state);
        }
    }
}
