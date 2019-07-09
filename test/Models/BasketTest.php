<?php
namespace App\Models;

use App\Exceptions\Missing_Product_Exception;
use PHPUnit\Framework\TestCase;

final class BasketTest extends TestCase
{
    var $basket = null;
    var $arrProducts = [];
    var $arrDeliveryRules = [];
    var $arrOffers = [];

    public function setUp(): void
    {
        $this->arrProducts = [
            "R01" => "3295",
            "G01" => "2495",
            "B01" => "795",
        ];
        $this->arrDeliveryRules = [
            [
                "operator" => "<",
                "threshold" => "5000",
                "price" => "495",
            ],
            [
                "operator" => "<",
                "threshold" => "9000",
                "price" => "295",
            ],
            [
                "operator" => ">=",
                "threshold" => "9000",
                "price" => "0",
            ],
        ];
        $this->arrOffers = [
            [
                "product" => "R01",
                "threshold" => "2",
                "discount" => "50%",
            ]
        ];
        $this->basket = new Basket($this->arrProducts, $this->arrDeliveryRules, $this->arrOffers);
    }

    public function testInitialized(): void
    {
        $this->assertInstanceOf(
            Basket::class,
            $this->basket
        );
        $this->assertEquals(
            $this->arrProducts,
            $this->basket->arrProducts
        );
        $this->assertEquals(
            $this->arrDeliveryRules,
            $this->basket->arrDeliveryRules
        );
        $this->assertEquals(
            $this->arrOffers,
            $this->basket->arrOffers
        );
    }

    public function testAddProductSuccess(): void
    {
        $bExpectedProductAdded = true;
        $strProductCode = 'R01';

        $bReceivedProductAdded = $this->basket->addProduct($strProductCode);
        $this->assertEquals($bExpectedProductAdded, $bReceivedProductAdded);
        $this->assertContains($strProductCode, $this->basket->_basket);
    }

    public function testAddProductError(): void
    {
        $strWrongProductCode = 'P01';

        $this->expectException(Missing_Product_Exception::class);
        $this->basket->addProduct($strWrongProductCode);
    }

    public function testGetTotal_first(): void
    {
        $expectedBasketTotal = 9827;
        $arrBasketItems = ['B01', 'B01', 'R01', 'R01', 'R01'];

        foreach($arrBasketItems as $strItem) {
            $this->basket->addProduct($strItem);
        }

        $receivedBasketTotal = $this->basket->getTotal();
        $this->assertEquals($expectedBasketTotal, $receivedBasketTotal);
    }

    public function testGetTotal_second(): void
    {
        $expectedBasketTotal = 6085;
        $arrBasketItems = ['R01', 'G01'];

        foreach($arrBasketItems as $strItem) {
            $this->basket->addProduct($strItem);
        }

        $receivedBasketTotal = $this->basket->getTotal();
        $this->assertEquals($expectedBasketTotal, $receivedBasketTotal);
    }

    public function testGetTotal_third(): void
    {
        $expectedBasketTotal = 5437;
        $arrBasketItems = ['R01', 'R01'];

        foreach($arrBasketItems as $strItem) {
            $this->basket->addProduct($strItem);
        }

        $receivedBasketTotal = $this->basket->getTotal();
        $this->assertEquals($expectedBasketTotal, $receivedBasketTotal);
    }
}

