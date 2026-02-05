<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'category_id', 'type_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
