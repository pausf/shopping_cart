<?php

namespace Cart\Product\Domain;

use Cart\Discount\Domain\Discount;

class Product
{

    private $productId;
    private $price;
    private $discount;


    public function __construct(ProductId $productId, ProductPrice $price, Discount $discount)
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->discount = $discount;
    }


    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }


    public function discount(): Discount
    {
        return $this->discount;
    }


}