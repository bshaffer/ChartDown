<?php

/**
* Repeat Start Expression
*/
class ChartDown_Chart_ExpressionType_RepeatStart implements ChartDown_Chart_ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '{:';
    }
    
    public function getPosition()
    {
        return 'bar';
    }
    
    public function getName()
    {
        return 'repeat start';
    }
    
    public function getRegex()
    {
        return '\{:';
    }
}
