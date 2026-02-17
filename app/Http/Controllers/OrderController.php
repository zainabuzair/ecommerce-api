<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Illuminate\Support\Facades\DB;

public function store(Request $request) {
    $request->validate([
        'items' => 'required|array',
        'items.*.id' => 'exists:products,id',
        'items.*.quantity' => 'required|integer|min:1'
    ]);

    return DB::transaction(function () use ($request) {
        $total = 0;
        
        // 1. Create the Order
        $order = auth()->user()->orders()->create(['total_price' => 0]);

        foreach ($request->items as $item) {
            $product = Product::lockForUpdate()->find($item['id']);
            
            // Check stock
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Product {$product->name} is out of stock.");
            }

            // 2. Create Order Items
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);

            $total += $product->price * $item['quantity'];
            
            // 3. Deduct Stock
            $product->decrement('stock', $item['quantity']);
        }

        // 4. Update total price
        $order->update(['total_price' => $total]);

        return response()->json($order->load('items.product'), 201);
    });
}
}
