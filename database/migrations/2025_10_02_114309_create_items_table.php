<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration {
//     public function up() {
//         Schema::create('items', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('category_id')->constrained()->cascadeOnDelete();
//             $table->string('name');
//             $table->decimal('price', 8, 2)->nullable();
//             $table->text('description')->nullable();
//             $table->string('image')->nullable(); // store filename/path if you want
//             $table->timestamps();
//         });
//     }
//     public function down() {
//         Schema::dropIfExists('items');
//     }
// };

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->default('L.L'); // example from screenshot
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // store path in storage/app/public/items
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('items');
    }
};

