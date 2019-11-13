<?php


namespace Cart\Product\Domain;


interface ProductRepository
{

    public function create(Product $product);
    public function find(ProductId $productId);
}