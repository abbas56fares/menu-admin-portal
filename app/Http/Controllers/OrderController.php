<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $itemsInput = collect($validated['items']);
        $items = Item::whereIn('id', $itemsInput->pluck('id'))->get()->keyBy('id');

        return DB::transaction(function () use ($validated, $itemsInput, $items) {
            $orderCurrency = 'LBP';
            $total = 0;

            $order = Order::create([
                'customer_name' => $validated['customer_name'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'total' => 0,
                'currency' => $orderCurrency,
            ]);

            foreach ($itemsInput as $payload) {
                $item = $items->get($payload['id']);
                if (!$item) {
                    continue;
                }

                $price = (float) ($item->price ?? 0);
                $quantity = (int) $payload['qty'];
                $lineTotal = $price * $quantity;
                $total += $lineTotal;

                if ($orderCurrency === 'LBP' && !empty($item->currency)) {
                    $orderCurrency = $item->currency;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'name' => $item->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'currency' => $item->currency ?? $orderCurrency,
                ]);
            }

            $order->update([
                'total' => $total,
                'currency' => $orderCurrency,
            ]);

            return redirect()->back()->with('success', 'Order placed successfully.');
        });
    }
}
