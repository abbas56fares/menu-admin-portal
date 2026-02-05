<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Type;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // === Categories ===
        $chops   = Category::firstOrCreate(['name' => 'Chops'], ['slug' => 'chops', 'logo' => 'chops.png']);
        $sicilia = Category::firstOrCreate(['name' => 'Sicilia'], ['slug' => 'sicilia', 'logo' => 'sicilia.png']);

        // === Types ===
        $food     = Type::firstOrCreate(['name' => 'Food'], ['icon' => 'bi-egg-fried']);
        $beverage = Type::firstOrCreate(['name' => 'Beverage'], ['icon' => 'bi-cup-straw']);
        $dessert  = Type::firstOrCreate(['name' => 'Dessert'], ['icon' => 'bi-cupcake']);
        $pizza    = Type::firstOrCreate(['name' => 'Pizza'], ['icon' => 'bi-pizza']);

        /*
        |--------------------------------------------------------------------------
        | Subcategories
        |--------------------------------------------------------------------------
        | Food subcategories differ between Chops and Sicilia.
        | Beverages, Desserts, and Pizza subcategories are shared across both.
        */

        // Food (CHOPS)
        $starters = Subcategory::firstOrCreate(['name'=>'Starters','category_id'=>$chops->id], ['slug'=>'starters','type_id'=>$food->id]);
        $grills   = Subcategory::firstOrCreate(['name'=>'Grills','category_id'=>$chops->id], ['slug'=>'grills','type_id'=>$food->id]);
        $fries    = Subcategory::firstOrCreate(['name'=>'Fries','category_id'=>$chops->id], ['slug'=>'fries','type_id'=>$food->id]);
        $snacks   = Subcategory::firstOrCreate(['name'=>'Snacks','category_id'=>$chops->id], ['slug'=>'snacks','type_id'=>$food->id]);

        // Food (SICILIA)
        $sushi   = Subcategory::firstOrCreate(['name'=>'Sushi','category_id'=>$sicilia->id], ['slug'=>'sushi','type_id'=>$food->id]);
        $different_pizzas = Subcategory::firstOrCreate(['name'=>'Different Pizzas','category_id'=>$sicilia->id], ['slug'=>'different-pizzas','type_id'=>$pizza->id]);

        // Shared subcategories (for both categories)
        $cocktails = Subcategory::firstOrCreate(['name'=>'Cocktail'], ['slug'=>'cocktail','type_id'=>$beverage->id,'category_id'=>$chops->id]);
        $smoothies = Subcategory::firstOrCreate(['name'=>'Smoothies'], ['slug'=>'smoothies','type_id'=>$beverage->id,'category_id'=>$chops->id]);
        $desserts  = Subcategory::firstOrCreate(['name'=>'Desserts'], ['slug'=>'desserts','type_id'=>$dessert->id,'category_id'=>$chops->id]);

        /*
        |--------------------------------------------------------------------------
        | Items Seeding
        |--------------------------------------------------------------------------
        */

        // --- CHOPS: FOOD ---
        $starters_items = [
            ['Chicken Tenders','Crispy breaded chicken strips',70000],
            ['Garlic Bread','Toasted bread with garlic butter',40000],
            ['Onion Rings','Golden fried onion rings',50000],
            ['Potato Skins','Loaded potato skins with cheese',60000],
        ];
        foreach ($starters_items as $f) {
            Item::firstOrCreate(
                ['name'=>$f[0],'category_id'=>$chops->id,'subcategory_id'=>$starters->id],
                ['description'=>$f[1],'price'=>$f[2],'currency'=>'L.L']
            );
        }

        $grill_items = [
            ['Grilled Chicken','Served with vegetables',110000],
            ['Beef Steak','Grilled tenderloin with sauce',150000],
            ['Lamb Chops','Juicy grilled lamb chops',160000],
            ['Mixed Grill','Combo of beef, chicken & lamb',180000],
        ];
        foreach ($grill_items as $f) {
            Item::firstOrCreate(
                ['name'=>$f[0],'category_id'=>$chops->id,'subcategory_id'=>$grills->id],
                ['description'=>$f[1],'price'=>$f[2],'currency'=>'L.L']
            );
        }

        $fries_items = [
            ['Cheese Fries','Crispy fries topped with cheddar',60000],
            ['Spicy Fries','Fries with chili powder & hot sauce',65000],
            ['Loaded Fries','Fries loaded with cheese & bacon bits',70000],
        ];
        foreach ($fries_items as $f) {
            Item::firstOrCreate(
                ['name'=>$f[0],'category_id'=>$chops->id,'subcategory_id'=>$fries->id],
                ['description'=>$f[1],'price'=>$f[2],'currency'=>'L.L']
            );
        }

        $snack_items = [
            ['Club Sandwich','Turkey, cheese & lettuce',95000],
            ['Chicken Wrap','Grilled chicken with veggies',90000],
            ['Mini Burgers','3 small sliders with cheese',110000],
        ];
        foreach ($snack_items as $f) {
            Item::firstOrCreate(
                ['name'=>$f[0],'category_id'=>$chops->id,'subcategory_id'=>$snacks->id],
                ['description'=>$f[1],'price'=>$f[2],'currency'=>'L.L']
            );
        }

        // --- SICILIA: FOOD (Sushi) ---
        $sushis = [
            ['California Roll','Crab & avocado roll',80000],
            ['Salmon Roll','Fresh salmon roll',90000],
            ['Tuna Roll','Tuna with sesame',95000],
            ['Philadelphia Roll','Salmon, cream cheese & cucumber',95000],
            ['Dragon Roll','Shrimp tempura & avocado',110000],
        ];
        foreach ($sushis as $s) {
            Item::firstOrCreate(
                ['name'=>$s[0],'category_id'=>$sicilia->id,'subcategory_id'=>$sushi->id],
                ['description'=>$s[1],'price'=>$s[2],'currency'=>'L.L']
            );
        }

        // --- SICILIA: PIZZA ---
        $pizza_items = [
            ['Margherita','Tomato, mozzarella & basil',90000],
            ['Pepperoni','Cheese, tomato sauce & pepperoni',95000],
            ['BBQ Chicken Pizza','Grilled chicken & BBQ sauce',100000],
            ['Vegetarian Pizza','Fresh veggies & mozzarella',85000],
            ['Four Cheese Pizza','Mozzarella, cheddar, feta, parmesan',105000],
        ];
        foreach ($pizza_items as $p) {
            Item::firstOrCreate(
                ['name'=>$p[0],'category_id'=>$sicilia->id,'subcategory_id'=>$different_pizzas->id],
                ['description'=>$p[1],'price'=>$p[2],'currency'=>'L.L']
            );
        }

        // --- SHARED: BEVERAGES (both Chops & Sicilia) ---
        $beverages = [
            ['Coca Cola','Classic Coke',20000],
            ['Pepsi','Classic Pepsi',20000],
            ['7Up','Lemon lime soda',20000],
            ['Fanta','Orange soda',20000],
            ['Water','Bottled mineral water',10000],
            ['Virgin Mojito','Mint, lime & soda',35000],
            ['Berry Blast','Mixed berries with ice',45000],
        ];
        foreach ([$chops, $sicilia] as $cat) {
            foreach ($beverages as $d) {
                Item::firstOrCreate(
                    ['name'=>$d[0],'category_id'=>$cat->id,'subcategory_id'=>$cocktails->id],
                    ['description'=>$d[1],'price'=>$d[2],'currency'=>'L.L']
                );
            }
        }

        // --- SHARED: SMOOTHIES (both Chops & Sicilia) ---
        $smoothie_items = [
            ['Banana Smoothie','Banana & milk blend',40000],
            ['Strawberry Smoothie','Fresh strawberries with milk',45000],
            ['Mango Smoothie','Tropical mango smoothie',45000],
        ];
        foreach ([$chops, $sicilia] as $cat) {
            foreach ($smoothie_items as $d) {
                Item::firstOrCreate(
                    ['name'=>$d[0],'category_id'=>$cat->id,'subcategory_id'=>$smoothies->id],
                    ['description'=>$d[1],'price'=>$d[2],'currency'=>'L.L']
                );
            }
        }

        // --- SHARED: DESSERTS (both Chops & Sicilia) ---
        $dessert_items = [
            ['Chocolate Cake','Moist chocolate cake',50000],
            ['Cheesecake','New York style cheesecake',60000],
            ['Brownie','Chocolate fudge brownie',45000],
            ['Ice Cream','2 scoops vanilla or chocolate',40000],
            ['Waffles','Belgian waffles with syrup',55000],
        ];
        foreach ([$chops, $sicilia] as $cat) {
            foreach ($dessert_items as $ds) {
                Item::firstOrCreate(
                    ['name'=>$ds[0],'category_id'=>$cat->id,'subcategory_id'=>$desserts->id],
                    ['description'=>$ds[1],'price'=>$ds[2],'currency'=>'L.L']
                );
            }
        }

        $this->command->info('✅ Items seeded successfully for both Chops and Sicilia (shared beverages, desserts, pizzas).');
    }
}
