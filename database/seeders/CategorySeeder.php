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
            'Nintendo Hardware', 
            'Nintendo Software', 
            'Sega Hardware',
            'Sega Software',
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
            'PC Hardware',
            'PC Software'
        ];

         foreach($categories as $category){

            Category::create([
                'name' => $category,
            ]);

         }   
        
        //Category::factory(20)->create();
    }
}
