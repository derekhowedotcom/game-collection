<?php

namespace Database\Seeders;

use App\Models\CollectionItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CollectionItem::factory(20)->create();
    }
}
