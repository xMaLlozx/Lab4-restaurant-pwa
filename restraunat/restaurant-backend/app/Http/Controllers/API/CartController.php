<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();
        return CartItemResource::collection($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $existing = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $request->quantity]);
            $item = $existing;
        } else {
            $item = CartItem::create([
                'user_id'    => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return new CartItemResource($item->load('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item = CartItem::where('user_id', $request->user()->id)->findOrFail($id);
        $item->update(['quantity' => $request->quantity]);
        return new CartItemResource($item->load('product'));
    }

    public function destroy(Request $request, $id)
    {
        CartItem::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['message' => 'Удалено из корзины']);
    }

    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'Корзина очищена']);
    }
}
