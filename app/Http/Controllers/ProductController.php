<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = auth()->user()->products;
        return response()->json(['success' => true, 'products' => new ProductCollection($products)]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->type = $request->type;
 

        if (auth()->user()->products()->save($product))
            return response()->json([
                'success' => true,
                'message' => 'product added successfully',
                'data' => $product->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'product can not be added'
            ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product) 
    {
        $product = auth()->user()->products()->find($product);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'product not found '
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductCollection($product)
        ], 400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $request['user_id'] = auth()->user()->id;
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 400);
        }
 
        $updated = $product->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Product can not be updated'
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 400);
        }
 
        if ($product->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product can not be deleted'
            ], 500);
        }

    }
}
