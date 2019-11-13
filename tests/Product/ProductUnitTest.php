<?php


namespace Cart\Tests\Product;

use Cart\Discount\Domain\Discount;
use Cart\Discount\Domain\DiscountFloat;
use Cart\Discount\Domain\DiscountUnit;
use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductPrice;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductRepository;
use Cart\Shared\Domain\Bus\Command\Command;
use Cart\Shared\Domain\Bus\Query\Query;
use PHPUnit\Framework\TestCase;
abstract class ProductUnitTest extends TestCase
{

    public $productRepository;

    /**
     * @before
     */
    public function setUpFixture()
    {
        $this->productRepository=$this->createMock(ProductRepository::class);
    }


    protected function dispatch(Command $command, callable $handler): void
    {
        $handler($command);
    }

    protected function ask(Query $query, callable $handler)
    {
        return $handler($query);
    }

    public function getProduct(): Product
    {
        $discount = new Discount(
            new DiscountFloat(10.0),
            new DiscountUnit(2)
        );

        return new Product(
            new ProductId('32416361-b8ec-4e00-84cc-b75b50945e21'),
            new ProductPrice(10),
            $discount
        );
    }

}