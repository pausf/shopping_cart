<?php


namespace Cart\Cart\Aplication\AddCartItem;


use Cart\Cart\Domain\Cart;
use Cart\Cart\Domain\CartId;
use Cart\Cart\Domain\CartRepository;
use Cart\CartItem\Domain\CartItem;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductRepository;
use Cart\Shared\Domain\Exception\Product\InvalidProductException;
use Cart\Shared\Domain\User\UserId;



class AddCartItem
{
    private $cartRepository;
    private $productRepository;


    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function add( CartId $cartId , UserId $userId ,ProductId $productId, CartItemAmount $amount)
    {

        $cart=$this->cartRepository->get($cartId);

        if (null === $cart) {
            $cart=Cart::create($cartId,$userId);
        }

        $product=$this->productRepository->find($productId);

        if (null === $product) {
            throw new InvalidProductException($productId->value().' not found');
        }

        $cart->addCartItem(new CartItem($product,$amount));

        $this->cartRepository->update($cart);
    }
}
