<?php

/**
* Tie Expression
*/
class ChartDown_Chart_ExpressionType_Tie implements ChartDown_Chart_ExpressionTypeInterface, ChartDown_Chart_Rhythm_RelativeMeterInterface
{
    public function getSymbol()
    {
        return '~';
    }
    
    public function getPosition()
    {
        return 'chord';
    }
    
    public function getName()
    {
        return 'tie';
    }
    
    public function getRegex()
    {
        return '~';
    }
    
    public function getRelativeMeter()
    {
        return 1;
    }
}
