<?php

/**
* Repeat Bar Expression
*/
class ChartDown_Chart_ExpressionType_RepeatBar implements ChartDown_Chart_ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '%';
    }
    
    public function getPosition()
    {
        return 'bar';
    }

    public function getName()
    {
        return 'repeat bar';
    }

    public function getRegex()
    {
        return '%';
    }
}
