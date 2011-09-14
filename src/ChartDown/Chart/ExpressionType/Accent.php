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
  
  public function isPreChordExpression()
  {
    return false;
  }
  
  public function getEnglishName()
  {
    return 'accent';
  }
  
  public function getRegex()
  {
    return '\^';
  }
}
