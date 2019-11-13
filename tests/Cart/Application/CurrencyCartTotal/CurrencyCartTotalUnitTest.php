<?php

namespace Cart\Tests\Cart\Application\CurrencyCartTotal;

use Cart\Cart\Aplication\CurrencyCartTotal\CurrencyCartTotal;
use Cart\Cart\Aplication\CurrencyCartTotal\CurrencyCartTotalCommand;
use Cart\Cart\Aplication\CurrencyCartTotal\CurrencyCartTotalCommandHandler;
use Cart\Tests\Cart\CartUnitTest;
use Cart\Tests\Cart\Domain\Factory\CartIdFactory;
use Cart\Tests\Currency\Domain\Factory\CurrencyCodeFactory;
use Cart\Tests\Shared\Domain\Factory\UserIdFactory;
use Ramsey\Uuid\Uuid as Ramsey;

class CurrencyCartTotalUnitTest extends CartUnitTest
{

    /** @test */
    public function it_should_change_currency_in_cart(): void
    {

        $cartId=Ramsey::uuid4()->toString();
        $userId=Ramsey::uuid4()->toString();
        $currencyCode='USD';

        $command=new CurrencyCartTotalCommand($cartId,$userId,$currencyCode);
        $handler=new CurrencyCartTotalCommandHandler(new CurrencyCartTotal($this->cartRepository,$this->currencyRepository));

        $cartId=CartIdFactory::create($command->getIdCart());
        $userId=UserIdFactory::create($command->getIdUser());
        $codeCurrency=CurrencyCodeFactory::create($command->getCodeCurrency());


        $product=$this->getProduct();
        $cart=$this->getCart($cartId,$userId);
        $exchangeRate='1.10';

        $this->cartRepository
            ->method('get')
            ->with($cart->getCartId())
            ->willReturn($cart);

        $this->currencyRepository
            ->method('get')
            ->with($codeCurrency)
            ->willReturn($exchangeRate);

        $this->cartRepository
            ->method('update')
            ->with($cart);


        $this->dispatch($command,$handler);

    }
}