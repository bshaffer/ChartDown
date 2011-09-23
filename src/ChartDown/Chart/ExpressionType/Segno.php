<?php

/**
* Segno Expression
*/
class ChartDown_Chart_ExpressionType_Segno implements ChartDown_Chart_ExpressionTypeInterface
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
