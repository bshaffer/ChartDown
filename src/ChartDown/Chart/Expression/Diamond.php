<?php

/**
* Diamond Expression
*/
class ChartDown_Chart_Expression_Diamond extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '*';
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
    return 'diamond';
  }
  
  public function getRegex()
  {
    return '\*';
  }
}
