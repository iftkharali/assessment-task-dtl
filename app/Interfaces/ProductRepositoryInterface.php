<?php

namespace App\Interfaces;

interface ProductRepositoryInterface 
{
    public function getAll();
    public function getById($productId);
    public function destroy($productId);
    public function update($productId, array $data);
    public function create(array $data);
}
