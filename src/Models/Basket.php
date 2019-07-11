<?php
namespace App\Models;

use App\Exceptions\Missing_Product_Exception;

class Basket
{
    public function __construct($arrProducts, $arrDeliveryRules, $arrOffers)
    {
        $this->arrProducts = $arrProducts;
        $this->arrDeliveryRules = $arrDeliveryRules;
        $this->arrOffers = $arrOffers;

        $this->_basket = []; // public for sake of UTs
    }

    public function getDeliveryRules()
    {
        return $this->arrDeliveryRules;
    }
    public function setDeliveryRules($arrDeliveryRules)
    {
        return $this->arrDeliveryRules = $arrDeliveryRules;
    }

    public function addProduct($strProductCode): bool
    {
        if (!array_key_exists($strProductCode, $this->arrProducts)) {
            throw new Missing_Product_Exception($strProductCode);
            return false;
        }

        $this->_basket[] = $strProductCode;
        return true;
    }
}