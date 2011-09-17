<?php

/**
* Diamond Expression
*/
class ChartDown_Chart_ExpressionType_Diamond implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return '*';
  }
  
  public function isChordExpression()
  {
    return true;
  }
  
  public function getName()
  {
    return 'diamond';
  }
  
  public function getRegex()
  {
    return '\*';
  }
}
