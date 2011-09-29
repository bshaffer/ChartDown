<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Tenudo Expression
*/
class Tenudo implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '_';
    }
    
    public function getPosition()
    {
        return 'chord';
    }
    
    public function getName()
    {
        return 'tenudo';
    }
    
    public function getRegex()
    {
        return '_';
    }
}
