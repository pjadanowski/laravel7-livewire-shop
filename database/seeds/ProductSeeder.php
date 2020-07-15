<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(0,20) as $i) {
            \App\Models\Product::create([
                'name' => $name = $faker->word,
                'slug' => \Illuminate\Support\Str::slug($name),
                'price' => $faker->numberBetween(20, 100),
                'discount_price' => $faker->numberBetween(10, 50),
                'short_description' => $faker->sentence,
                'description' => $faker->sentence(10),
                'quantity' => $faker->numberBetween(10, 36),
                'category_id' => $faker->numberBetween(10, 36)
            ]);
        }
    }
}
