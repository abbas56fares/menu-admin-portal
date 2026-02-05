<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === Categories ===
        $chops = \App\Models\Category::firstOrCreate([
            'name' => 'Chops',
        ], [
            'slug' => 'chops',
            'logo' => 'chops.png',
        ]);

        $sicilia = \App\Models\Category::firstOrCreate([
            'name' => 'Sicilia',
        ], [
            'slug' => 'sicilia',
            'logo' => 'sicilia.png',
        ]);

        // === Types ===
        $food = \App\Models\Type::firstOrCreate(['name' => 'Food'], ['icon' => 'bi-egg-fried']);
        $beverage = \App\Models\Type::firstOrCreate(['name' => 'Beverage'], ['icon' => 'bi-cup-straw']);
        $dessert = \App\Models\Type::firstOrCreate(['name' => 'Dessert'], ['icon' => 'bi-cupcake']);

        // === Subcategories (Chops) ===
        $starters = \App\Models\Subcategory::firstOrCreate(
            ['name' => 'Starters', 'category_id' => $chops->id],
            ['slug' => 'starters', 'type_id' => $food->id]
        );

        $grills = \App\Models\Subcategory::firstOrCreate(
            ['name' => 'Grills', 'category_id' => $chops->id],
            ['slug' => 'grills', 'type_id' => $food->id]
        );

        // === Subcategories (Sicilia) ===
        $sushi = \App\Models\Subcategory::firstOrCreate(
            ['name' => 'Sushi', 'category_id' => $sicilia->id],
            ['slug' => 'sushi', 'type_id' => $food->id]
        );

        $pizza = \App\Models\Subcategory::firstOrCreate(
            ['name' => 'Pizza', 'category_id' => $sicilia->id],
            ['slug' => 'pizza', 'type_id' => $food->id]
        );

        // $drinks = \App\Models\Subcategory::firstOrCreate(
        //     ['name' => 'Drinks', 'category_id' => $sicilia->id],
        //     ['slug' => 'drinks', 'type_id' => $beverage->id]
        // );

        // Items are now seeded in ItemSeeder
        $this->call(ItemSeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
