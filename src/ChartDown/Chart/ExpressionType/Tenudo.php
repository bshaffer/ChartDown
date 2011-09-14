<?php

/**
* Tenudo Expression
*/
class ChartDown_Chart_ExpressionType_Tenudo implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return '_';
  }
  
  public function isChordExpression()
  {
    return true;
  }
  
  public function isPreChordExpression()
  {
    return false;
  }
  
  public function getEnglishName()
  {
    return 'tenudo';
  }
  
  public function getRegex()
  {
    return '_';
  }
}
