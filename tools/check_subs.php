<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subcategory;
use App\Models\Item;

$names = ['Starters','Grills','Sushi','Pizza','Fries','Snacks'];
foreach ($names as $n) {
    $s = Subcategory::where('name',$n)->first();
    if (!$s) { echo "$n: MISSING\n"; continue; }
    $count = Item::where('subcategory_id',$s->id)->count();
    echo "$n (id={$s->id}): $count items\n";
}
