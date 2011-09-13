<?php

/**
* Segno Expression
*/
class ChartDown_Chart_Expression_Segno extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '&';
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
    return 'segno';
  }
  
  public function getRegex()
  {
    return '&';
  }
}
