<?php

/**
* Repeat Bar Expression
*/
class ChartDown_Chart_Expression_RepeatBar extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '%';
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
    return 'repeat bar';
  }
  
  public function getRegex()
  {
    return '%';
  }
}
