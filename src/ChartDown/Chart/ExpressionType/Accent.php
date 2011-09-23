<?php

/**
* Accent Expression
*/
class ChartDown_Chart_ExpressionType_Accent implements ChartDown_Chart_ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '^';
    }
  
    public function getPosition()
    {
        return 'chord';
    }
  
    public function getName()
    {
        return 'accent';
    }

    public function getRegex()
    {
        return '\^';
    }
}
