<?php

/**
* Repeat Ending Expression
*/
class ChartDown_Chart_ExpressionType_RepeatEnding implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return '{num}';
  }
  
  public function isChordExpression()
  {
    return false;
  }
  
  public function getName()
  {
    return 'repeat ending';
  }
  
  public function getRegex()
  {
    return '\{(\d+)\}';
  }
}