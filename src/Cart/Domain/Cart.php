<?php

namespace Cart\Cart\Domain;


use Cart\Cart\Domain\Exceptions\ProductDifferentMaxInCartException;
use Cart\CartItem\Domain\CartItem;
use Cart\Currency\Domain\Currency;
use Cart\Currency\Domain\CurrencyCode;
use Cart\Currency\Domain\CurrencyExchange;
use Cart\Product\Domain\ProductId;
use Cart\Shared\Domain\Exception\Product\InvalidProductException;
use Cart\Shared\Domain\User\UserId;

class Cart
{
    const MAX_ITEMS = 10;
    const CURRENCY_CODE='EUR';
    CONST CURRENCY_RATE=1.0;

    private $cartId;
    private $userId;
    private $currency;
    private $items=[];

    public function __construct( CartId $cartId, UserId $userId,Currency $currency=null ,array $items=null)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
        $this->currency = $currency;
        $this->items=$items;
    }

    public static function create(CartId $cartId, UserId $userId, Currency $currency=null):Cart
    {
        if(!is_null($currency)){
            return new self($cartId,$userId,$currency);
        }

        $currency=Currency::create(new CurrencyCode(self::CURRENCY_CODE),new CurrencyExchange(self::CURRENCY_RATE));

        return new self($cartId,$userId,$currency);
    }


    public function addCartItem(CartItem $cartItem){

        if(isset($this->items[$cartItem->getProduct()->productId()->value()])) {

            $item = $this->items[$cartItem->getProduct()->productId()->value()];
            $item->increment($cartItem->getAmount()->value()) ;

        }else{

            $this->checkMaxItemsInCart();
            $this->items[$cartItem->getProduct()->productId()->value()]=$cartItem;
        }
    }

    public function changeCurrency(Currency $currency)
    {
        $this->currency=$currency;
    }


    public function removeCartItem(ProductId $productId)
    {

        if(!isset($this->items[$productId->value()])) {
            throw new InvalidProductException('The product is not in the cart');
        }

        unset($this->items[$productId->value()]);

    }

    private function checkMaxItemsInCart()
    {

        if(isset($this->items)) {
            if ($this->count() > self::MAX_ITEMS) {
                throw new ProductDifferentMaxInCartException('The cart is full');
            }
        }
    }

    public function count(){
        return count($this->items);
    }

    public function getCartId(): CartId
    {
        return $this->cartId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getTotal()
    {
        $total=0;
        $totalDiscount=0;

        foreach($this->items as $item){
           $total+=$item->getTotalItems();
           $totalDiscount+=$item->getTotalItemsWitchDiscount();
        }

        $data=[
            self::CURRENCY_CODE=>$total,
            'DISCOUNT'=>$totalDiscount
        ];

        if($this->currency->getCurrencyCode()->value()==self::CURRENCY_CODE)
        {
            return $data;
        }

       return $this->withCurrencyApplied($total, $data, $totalDiscount);

    }

    public function withCurrencyApplied(int $total, array $data, int $totalDiscount): array
    {
        $data[$this->currency->getCurrencyCode()->value()] = $total * $this->currency->getCurrencyExchange()->value();
        $data['DISCOUNT_' . $this->currency->getCurrencyCode()->value()] = $totalDiscount * $this->currency->getCurrencyExchange()->value();
        return $data;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getItem(): array
    {
        return $this->items;
    }

}