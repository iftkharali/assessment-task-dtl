<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductsFilterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = User::findorFail($id);
        $products = $user->products;
        return response()->json(['success' => true, $user->name.'-products' => new ProductCollection($products)]); 

    }

    public function search($search_term){
        $product = Product::where('name', $search_term)->get();
        return response()->json(['success' => true, 'filtered-products-with-name' => new ProductCollection($product)]); 
    }
}
