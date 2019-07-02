<?php
namespace App\Models;

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
                "discount" => "25%",
            ]
        ];
        $this->basket = new Basket($this->arrProducts, $this->arrDeliveryRules, $this->arrOffers);
    }
}

