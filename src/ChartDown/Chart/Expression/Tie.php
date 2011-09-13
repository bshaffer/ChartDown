<?php

/**
* Tie Expression
*/
class ChartDown_Chart_Expression_Tie extends ChartDown_Chart_Expression
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
