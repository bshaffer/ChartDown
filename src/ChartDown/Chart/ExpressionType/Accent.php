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
  
    public function isChordExpression()
    {
        return true;
    }
  
    public function getName()
    {
        return 'accent';
    }

    public function getRegex()
    {
        return '\^';
    }
  
    public function getType()
    {
        return ChartDown_Chart_Expression::ACCENT;
    }
}
