<?php
namespace App\Models;

class Basket
{
    public function __construct($arrProducts, $arrDeliveryRules, $arrOffers)
    {
        $this->arrProducts = $arrProducts;
        $this->arrDeliveryRules = $arrDeliveryRules;
        $this->arrOffers = $arrOffers;
    }
}