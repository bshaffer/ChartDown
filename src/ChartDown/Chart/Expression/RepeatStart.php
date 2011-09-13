<?php

/**
* Repeat Start Expression
*/
class ChartDown_Chart_Expression_RepeatStart extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '{:';
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
    return 'repeat start';
  }
  
  public function getRegex()
  {
    return '\{:';
  }
}
