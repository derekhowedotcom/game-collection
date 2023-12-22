<?php

namespace Database\Seeders;

use App\Models\Rarity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RaritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rarityNames = [
            'Common',
            'Uncommon',
            'Rare',
            'Grail',
        ];

         foreach($rarityNames as $rarityName){

             Rarity::create([
                'name' => $rarityName,
            ]);

         }

        //Category::factory(20)->create();
    }
}
