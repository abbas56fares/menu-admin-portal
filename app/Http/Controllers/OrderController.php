<?php

namespace App\Http\Controllers;

use App\Services\StaticDataService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        // Get items from static data instead of database
        $itemsInput = collect($validated['items']);
        $allItems = StaticDataService::getAllItems(true);
        $items = $allItems->keyBy('id');

        // Calculate order total (without actually saving to database)
        $orderCurrency = 'USD';
        $total = 0;
        $orderItems = [];

        foreach ($itemsInput as $payload) {
            $item = $items->get($payload['id']);
            if (!$item) {
                continue;
            }

            $price = (float) ($item->price ?? 0);
            $quantity = (int) $payload['qty'];
            $lineTotal = $price * $quantity;
            $total += $lineTotal;

            if (!empty($item->currency)) {
                $orderCurrency = $item->currency;
            }

            $orderItems[] = [
                'name' => $item->name,
                'price' => $price,
                'quantity' => $quantity,
                'currency' => $item->currency ?? $orderCurrency,
                'line_total' => $lineTotal,
            ];
        }

        // In static mode, we don't actually save to database
        // Instead, we could store in session or just return success
        session()->flash('order_success', [
            'customer_name' => $validated['customer_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'items' => $orderItems,
            'total' => $total,
            'currency' => $orderCurrency,
        ]);

        return redirect()->back()->with('success', 'Order placed successfully! (Static Mode - Not saved to database)');
    }
}
