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
  
  public function isChordExpression()
  {
    return true;
  }
  
  public function isPreChordExpression()
  {
    return true;
  }
  
  public function getEnglishName()
  {
    return 'anticipation';
  }
  
  public function getRegex()
  {
    return '>';
  }
}
