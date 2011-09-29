<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Segno Expression
*/
class Segno implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '&';
    }
    
    public function getPosition()
    {
        return 'top';
    }
    
    public function getName()
    {
        return 'segno';
    }
    
    public function getRegex()
    {
        return '&';
    }
}
