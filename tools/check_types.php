<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Type;
use App\Models\Item;

foreach (Type::with('subcategories.items')->get() as $t) {
    $count = collect($t->subcategories)->flatMap->items->count();
    echo "{$t->id} - {$t->name} : {$count}\n";
}

$qa = Item::where('name','QA Beverage Item')->get();
echo "QA items found: " . $qa->count() . "\n";
foreach($qa as $it) echo "- ID: {$it->id}, category_id: {$it->category_id}, subcategory_id: {$it->subcategory_id}\n";
