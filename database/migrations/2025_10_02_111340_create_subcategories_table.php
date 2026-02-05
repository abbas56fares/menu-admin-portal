<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Burger, Sushi, Pasta
            $table->string('slug')->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // Chops or Sicilia
            $table->foreignId('type_id')->nullable()->constrained('types')->nullOnDelete(); // Food/Beverage/Dessert
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('subcategories');
    }
};
