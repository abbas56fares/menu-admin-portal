<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float|null $price
 * @property string|null $currency
 * @property string|null $image
 * @property int|null $category_id
 * @property int|null $subcategory_id
 * @property int|null $type_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Subcategory|null $subcategory
 * @property-read \App\Models\Type|null $type
 */
class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'image',
        'is_active',
        'category_id',
        'subcategory_id',
        'type_id', // <-- make sure this column exists in your items table
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
