<?php
namespace App\Models;

use App\Exceptions\Missing_Product_Exception;
use App\Exceptions\Unrecognized_Operator_Exception;

class Basket
{
    var $_deliveryCost = 0;

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

    public function getTotal(): int
    {
        $intAccumulator = 0;

        foreach ($this->_basket as $strProductCode) {
            if (!array_key_exists($strProductCode, $this->arrProducts)) {
                throw new Missing_Product_Exception($strProductCode);
            }

            $intAccumulator += $this->arrProducts[$strProductCode];
        }

        foreach ($this->arrOffers as $arrOffer) {
            $arrProductQuantities = array_count_values($this->_basket);
            if ($arrOffer['threshold'] <= $arrProductQuantities[$arrOffer['product']]) {
                $strDiscount = $arrOffer['discount'];
                if (false !== strpos($arrOffer['discount'], '%')) {
                    $strDiscount = str_replace('%', '', $strDiscount);
                    $intMultiplier = 1 - (((int)$strDiscount) / 100);
                }
                $intAccumulator -= $intMultiplier * $this->arrProducts[$arrOffer['product']];
            }
        }

        $bFoundDeliveryRule = false;
        foreach ($this->arrDeliveryRules as $arrDeliveryRule) {
            if ($bFoundDeliveryRule) {
                break;
            }
            $strOperator = $arrDeliveryRule['operator'];
            switch ($strOperator) {
                case '<':
                    if ($intAccumulator < $arrDeliveryRule['threshold']) {
                        $this->_deliveryCost += $arrDeliveryRule['price'];
                        $bFoundDeliveryRule = true;
                    }
                    break;
                case '>':
                    if ($intAccumulator > $arrDeliveryRule['threshold']) {
                        $this->_deliveryCost += $arrDeliveryRule['price'];
                        $bFoundDeliveryRule = true;
                    }
                    break;
                case '<=':
                    if ($intAccumulator <= $arrDeliveryRule['threshold']) {
                        $this->_deliveryCost += $arrDeliveryRule['price'];
                        $bFoundDeliveryRule = true;
                    }
                    break;
                case '>=':
                    if ($intAccumulator <= $arrDeliveryRule['threshold']) {
                        $this->_deliveryCost += $arrDeliveryRule['price'];
                        $bFoundDeliveryRule = true;
                    }
                    break;
                default:
                    throw new Unrecognized_Operator_Exception($arrDeliveryRule['operator']);
                    break;
            }
        }

        return $intAccumulator + $this->_deliveryCost;
    }
}