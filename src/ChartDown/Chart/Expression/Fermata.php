<?php

/**
* Fermata Expression
*/
class ChartDown_Chart_Expression_Fermata extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '!';
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
    return 'fermata';
  }
  
  public function getRegex()
  {
    return '!';
  }
}
