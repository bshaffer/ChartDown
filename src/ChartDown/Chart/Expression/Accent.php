<?php

/**
* Accent Expression
*/
class ChartDown_Chart_Expression_Accent extends ChartDown_Chart_Expression
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
