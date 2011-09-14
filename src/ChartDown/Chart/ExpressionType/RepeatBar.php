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
  
  public function isChordExpression()
  {
    return false;
  }
  
  public function isPreChordExpression()
  {
    return false;
  }
  
  public function getEnglishName()
  {
    return 'repeat bar';
  }
  
  public function getRegex()
  {
    return '%';
  }
}
