<?php
namespace App\Exceptions;

class Missing_Product_Exception extends \Exception
{
    public function __construct($strProductCode)
    {
        $this->strProductCode = $strProductCode;
    }

    public function __toString()
    {
        return sprintf('Product "%s" does not exist in given catalogue.', $strProductCode);
    }
}