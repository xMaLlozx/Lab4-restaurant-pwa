<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Orders", description="Заказы")
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/orders",
     *   tags={"Orders"},
     *   summary="Список заказов пользователя",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=200, description="Список заказов")
     * )
     */
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return new OrderCollection($orders);
    }

    /**
     * @OA\Post(
     *   path="/api/orders",
     *   tags={"Orders"},
     *   summary="Создать заказ",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"address","payment_method"},
     *       @OA\Property(property="address", type="string", example="ул. Ленина, 1"),
     *       @OA\Property(property="payment_method", type="string", example="card"),
     *       @OA\Property(property="comment", type="string", example="Без лука")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Заказ создан"),
     *   @OA\Response(response=400, description="Корзина пуста")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:card,cash',
            'comment'        => 'nullable|string|max:500',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Корзина пуста'], 400);
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        $order = Order::create([
            'user_id'        => $request->user()->id,
            'address'        => $request->address,
            'payment_method' => $request->payment_method,
            'comment'        => $request->comment,
            'total'          => $total,
            'status'         => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        CartItem::where('user_id', $request->user()->id)->delete();

        return new OrderResource($order->load('items.product'));
    }

    /**
     * @OA\Get(
     *   path="/api/orders/{id}",
     *   tags={"Orders"},
     *   summary="Заказ по ID",
     *   security={{"sanctum":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Данные заказа"),
     *   @OA\Response(response=403, description="Доступ запрещён")
     * )
     */
    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        return new OrderResource($order);
    }
}
