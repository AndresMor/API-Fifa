<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Player;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        $response = Http::get('https://www.easports.com/fifa/ultimate-team/api/fut/item',[
            'page' => 1,
        ]);
        $last_page = $response['totalPages'];
        foreach ($response['items'] as $player) {
            Player::firstOrCreate(
                ['name' => $player['name'],'club' => $player['club']['name']],
                ['position' => $player['position'],'nation' => $player['nation']['name']]
            );
        }
        for ($i=1; $i < $last_page; $i++) { 
            $response = Http::get('https://www.easports.com/fifa/ultimate-team/api/fut/item',[
                'page' => $i+1,
            ]);
            foreach ($response['items'] as $player) {
                Player::firstOrCreate(
                    ['name' => $player['name'],'club' => $player['club']['name']],
                    ['position' => $player['position'],'nation' => $player['nation']['name']]
                );
            }
        }
    }
}
