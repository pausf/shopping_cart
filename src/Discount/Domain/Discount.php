<?php

namespace Cart\Discount\Domain;

class Discount
{

    private $discountPrice;
    private $discountUnit;

    public function __construct(DiscountFloat $discountPrice, DiscountUnit $discountUnit)
    {
        $this->discountPrice = $discountPrice;
        $this->discountUnit = $discountUnit;
    }

    public function discountPrice(): DiscountFloat
    {
        return $this->discountPrice;
    }

    public function discountUnit(): DiscountUnit
    {
        return $this->discountUnit;
    }

}