<?php

/**
* Anticipation Expression
*/
class ChartDown_Chart_Expression_Anticipation extends ChartDown_Chart_Expression
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
