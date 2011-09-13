<?php

/**
* Tenudo Expression
*/
class ChartDown_Chart_Expression_Tenudo extends ChartDown_Chart_Expression
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
