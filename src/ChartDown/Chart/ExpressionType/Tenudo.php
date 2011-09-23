<?php

/**
* Tenudo Expression
*/
class ChartDown_Chart_ExpressionType_Tenudo implements ChartDown_Chart_ExpressionTypeInterface
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
