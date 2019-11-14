<?php

namespace Cart\CartItem\Domain;

use Cart\Product\Domain\Product;

class CartItem
{
    private $product;
    private $amount;


    public function __construct(Product $product, CartItemAmount $amount)
    {
        $this->product = $product;
        $this->amount = $amount;
    }


    public function getProduct(): Product
    {
        return $this->product;
    }


    public function getAmount(): CartItemAmount
    {
        return $this->amount;
    }

    public function increment($amount)
    {
        $this->amount = new CartItemAmount($this->amount->value() + $amount);
    }

    public function getTotalItems()
    {
        return $this->product->price()->value() * $this->amount->value();
    }

    public function getTotalItemsWitchDiscount()
    {
        if ($this->product->discount()->discountUnit()->value() <= $this->amount->value()) {
            return $this->applyDiscountPrice();
        }
        return $this->product->price()->value() * $this->amount->value();
    }

    public function applyDiscountPrice()
    {
        return $priceDiscount = $this->amount->value() * $this->product->discount()->discountPrice()->value();
    }

}