<?php

/**
* Repeat Finish Expression
*/
class ChartDown_Chart_ExpressionType_RepeatFinish implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return ':}';
  }
  
  public function isChordExpression()
  {
    return false;
  }
  
  public function getName()
  {
    return 'repeat finish';
  }
  
  public function getRegex()
  {
    return ':\}';
  }
}