<?php

/**
* Coda Expression
*/
class ChartDown_Chart_ExpressionType_Coda implements ChartDown_Chart_ExpressionTypeInterface, ChartDown_Chart_Rhythm_RelativeMeterInterface
{
    public function getSymbol()
    {
        return '$';
    }

    public function getPosition()
    {
        return 'top';
    }
    
    public function getName()
    {
        return 'coda';
    }

    public function getRegex()
    {
        return '\$';
    }
    
    public function getRelativeMeter()
    {
        return 0;
    }
}
