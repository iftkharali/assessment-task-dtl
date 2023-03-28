<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;


class ProductRepository implements ProductRepositoryInterface 
{
    public function getAll() 
    {
        return Product::orderBy('id', 'desc')->get();
    }
    
    public function getById($postId) 
    {
        return Product::findOrFail($postId);
    }

    public function destroy($postId) 
    {
        return Product::destroy($postId);
    }
    
    public function update($postId, array $data) 
    {
        return Product::whereId($postId)->update($data);
    }

    public function create(array $data) 
    {
        return  auth()->user()->products()->create($data);
    }
}