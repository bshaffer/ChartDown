<?php

/**
* Tie Expression
*/
class ChartDown_Chart_ExpressionType_Tie implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return '~';
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
    return 'tie';
  }
  
  public function getRegex()
  {
    return '~';
  }
}
