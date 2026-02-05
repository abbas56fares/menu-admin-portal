<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\MenuController;

$ctrl = new MenuController();
foreach (['chops','sicilia'] as $brand) {
    echo "---- $brand ----\n";
    $resp = $ctrl->index($brand);
    $viewData = $resp->getData();
    $cat = $viewData['category'];
    foreach ($cat->subcategories as $sub) {
        echo $sub->id . ' - ' . $sub->name . ' (' . ($sub->type->name ?? 'NoType') . ') - category: ' . ($sub->category->name ?? 'N/A') . "\n";
    }
}
