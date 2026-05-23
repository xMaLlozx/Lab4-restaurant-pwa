<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Products", description="Товары / Блюда")
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/products",
     *   tags={"Products"},
     *   summary="Список всех блюд",
     *   @OA\Parameter(name="category_id", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
     *   @OA\Response(response=200, description="Список блюд")
     * )
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);

        return new ProductCollection($products);
    }

    /**
     * @OA\Get(
     *   path="/api/products/{id}",
     *   tags={"Products"},
     *   summary="Получить блюдо по ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Данные блюда"),
     *   @OA\Response(response=404, description="Не найдено")
     * )
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);
    }
}
