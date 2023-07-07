<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Atari Jaguar Hardware', 
            'Atari Jaguar Software',
            'Atari Lynx Hardware', 
            'Nintendo Lynx Software',
            'Nintendo 3DS Hardware', 
            'Nintendo 3DS Software', 
            'Nintendo DS Hardware', 
            'Nintendo DS Software', 
            'Nintendo 64 Hardware', 
            'Nintendo 64 Software', 
            'Nintendo Entertainment System Hardware', 
            'Nintendo Entertainment System Software', 
            'Nintendo Game Boy Hardware', 
            'Nintendo Game Boy Software', 
            'Nintendo Gamecube Hardware', 
            'Nintendo GameCube Software', 
            'Nintendo Switch Hardware', 
            'Nintendo Switch Software', 
            'Nintendo Virtual Boy Hardware', 
            'Nintendo Virtual Boy Software', 
            'Nintendo Wii Hardware', 
            'Nintendo Wii Software', 
            'Nintendo Wii U Hardware', 
            'Nintendo Wii U Software', 
            'Sega Dreamcast Hardware',
            'Sega Dreamcast Software',
            'Sega Game Gear Hardware',
            'Sega Game Gear Software',
            'Sega Master System Hardware',
            'Sega Master System Software',
            'Sega Mega 32X Hardware',
            'Sega Mega 32X Software',
            'Sega Mega CD Hardware',
            'Sega Mega CD Software',
            'Sega Mega Drive Hardware',
            'Sega Mega Drive Software',
            'Sega Saturn Hardware',
            'Sega Saturn Software',
            'Super Nintendo Hardware', 
            'Super Nintendo Software', 
            'PlayStation 1 Hardware',
            'PlayStation 1 Software',
            'PlayStation 2 Hardware',
            'PlayStation 2 Software',
            'PlayStation 3 Hardware',
            'PlayStation 3 Software',
            'PlayStation 4 Hardware',
            'PlayStation 4 Software',
            'PlayStation 5 Hardware',
            'PlayStation 5 Software',
            'PlayStation Portable Hardware',
            'PlayStation Portable Software',
            'PlayStation Vita Hardware',
            'PlayStation Vita Software',
            'PC Hardware',
            'PC Software',
            'Xbox Hardware',
            'Xbox Software',
            'Xbox 360 Hardware',
            'Xbox 360 Software',
            'Xbox One Hardware',
            'Xbox One Software',
            'Xbox Series Hardware',
            'Xbox Series Software',
            'Other',
        ];

         foreach($categories as $category){

            Category::create([
                'name' => $category,
            ]);

         }   
        
        //Category::factory(20)->create();
    }
}
