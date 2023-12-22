<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Rarity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CollectionItem>
 */
class CollectionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $categoryIds = Category::pluck('id');
        $rarityIds = Rarity::pluck('id');

        return [
            'title' => $this->faker->text(20),
            'description' => $this->faker->paragraphs(5, true),
            'category_id' => $categoryIds->random(),
            'value' => $this->faker->randomFloat(2, 0, 1000),
            'price_paid' => $this->faker->randomFloat(2, 0, 1000),
            'barcode' => $this->faker->ean13(),
            // Make boxed 1 or 0
            'boxed' => $this->faker->boolean(50),
            // Make rarity_id null or random
            'rarity_id' => $this->faker->boolean(50) ? $rarityIds->random() : null,
        ];
    }
}
