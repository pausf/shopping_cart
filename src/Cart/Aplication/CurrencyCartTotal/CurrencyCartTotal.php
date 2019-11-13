<?php

namespace Cart\Cart\Aplication\CurrencyCartTotal;

use Cart\Cart\Domain\CartRepository;
use Cart\Cart\Domain\Exceptions\CartNotExistException;
use Cart\Currency\Domain\Currency;
use Cart\Currency\Domain\CurrencyExchange;
use Cart\Currency\Domain\CurrencyRepository;

class CurrencyCartTotal
{

    private $cartRepository;
    private $currencyRepository;

    public function __construct(CartRepository $cartRepository , CurrencyRepository $currencyRepository  )
    {
        $this->cartRepository = $cartRepository;
        $this->currencyRepository = $currencyRepository;
    }

    public function changeCurrency($cartId,$userId,$currencyCode)
    {

        $cart=$this->cartRepository->get($cartId);

        if (null === $cart) {
            throw new CartNotExistException($cartId->value().' not found');
        }

        $rate=$this->currencyRepository->get($currencyCode);

        $currency=Currency::create($currencyCode,new CurrencyExchange($rate));
        $cart->changeCurrency($currency);

        $this->cartRepository->update($cart);

    }
}