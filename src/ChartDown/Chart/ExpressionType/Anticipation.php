<?php

/**
* Anticipation Expression
*/
class ChartDown_Chart_ExpressionType_Anticipation implements ChartDown_Chart_ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '>';
    }

    public function getPosition()
    {
        return 'chord';
    }
  
    public function getName()
    {
        return 'anticipation';
    }

    public function getRegex()
    {
        return '>';
    }
}
