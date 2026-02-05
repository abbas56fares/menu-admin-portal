<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for foreign keys and frequently queried columns
        Schema::table('items', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('type_id');
            $table->index('is_active');
            $table->index(['category_id', 'is_active']);
            $table->index(['subcategory_id', 'is_active']);
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('type_id');
            $table->index('slug');
            $table->index(['category_id', 'type_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex(['items_category_id_index']);
            $table->dropIndex(['items_subcategory_id_index']);
            $table->dropIndex(['items_type_id_index']);
            $table->dropIndex(['items_is_active_index']);
            $table->dropIndex(['items_category_id_is_active_index']);
            $table->dropIndex(['items_subcategory_id_is_active_index']);
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropIndex(['subcategories_category_id_index']);
            $table->dropIndex(['subcategories_type_id_index']);
            $table->dropIndex(['subcategories_slug_index']);
            $table->dropIndex(['subcategories_category_id_type_id_index']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['categories_slug_index']);
        });
    }
};
