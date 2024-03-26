<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
        // return response(['error'=>true,'error-msg'=> 'there is error'],404);

    }

    public function getProduct(Request $request)
    {
        $product = Product::find(['id' => $request->id]);
        return response()->json(['product' => $product]);
    }

    public function createProducts()
    {

        $resp = Http::get('https://fakestoreapi.com/products');
        $coll = collect($resp->json());
        $coll->each(function ($collection, $alphabet) {
            // dump($collection['title']);
            $product = new Product;
            $product->title = $collection['title'];
            $product->price = $collection['price'];
            $product->description = $collection['description'];
            $product->category = $collection['category'];
            $product->image = $collection['image'];
            $product->rating_rate = $collection['rating']['rate'];
            $product->rating_count = $collection['rating']['count'];
            $product->save();

        });
    }
}
