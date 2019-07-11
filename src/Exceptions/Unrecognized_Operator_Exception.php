<?php
namespace App\Exceptions;

class Unrecognized_Operator_Exception extends \Exception
{
    public function __construct($strOperator)
    {
        $this->strOperator = $strOperator;
    }

    public function __toString()
    {
        return sprintf('Operator "%s" was not recognized.', $strOperator);
    }
}