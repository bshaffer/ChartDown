<?php

/**
* Coda Expression
*/
class ChartDown_Chart_Expression_Coda extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '$';
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
    return 'coda';
  }
  
  public function getRegex()
  {
    return '\$';
  }
}
