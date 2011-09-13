<?php

/**
* Repeat Finish Expression
*/
class ChartDown_Chart_Expression_RepeatFinish extends ChartDown_Chart_Expression
{
  public function getSymbol()
  {
    return ':}';
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
    return 'repeat finish';
  }
  
  public function getRegex()
  {
    return ':\}';
  }
}