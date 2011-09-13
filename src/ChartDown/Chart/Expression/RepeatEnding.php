<?php

/**
* Repeat Ending Expression
*/
class ChartDown_Chart_Expression_RepeatEnding extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return '{num}';
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
    return 'repeat ending';
  }
  
  public function getRegex()
  {
    return '\{(\d+)\}';
  }
}
