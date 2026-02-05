<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $logo
 * @property string|null $brand
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subcategory> $subcategories
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 */
class Category extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','logo','brand'];

    public function subcategories() {
        return $this->hasMany(Subcategory::class);
    }
    public function items() {
        return $this->hasMany(Item::class);
    }
}
