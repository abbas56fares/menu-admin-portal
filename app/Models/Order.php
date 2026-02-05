<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $customer_name
 * @property string|null $phone
 * @property string|null $notes
 * @property string $status
 * @property float $total
 * @property string $currency
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone',
        'notes',
        'status',
        'total',
        'currency',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
