<?php

namespace App\Http\Controllers;

use App\Events\ProductAdded;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{

    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository) 
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productRepository->getAll();
        return response()->json(['success' => true, 'products' => new ProductCollection($products)]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $input = $request->except('_token');
        $product = $this->productRepository->create($input);
        if ($product)
        {
            event(new ProductAdded($product));
            return response()->json([
                'success' => true,
                'message' => 'product added successfully',
                'data' => $product->toArray()
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'product can not be added'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product) 
    {
        $product = $this->productRepository->getById($product->id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'product not found '
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => $product
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

        $updated = $this->productRepository->update($product->id, $request->except(['_token','_method'])); 
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
 
        $productDeleted = $this->productRepository->destroy($product->id);

        if ($productDeleted) {
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
