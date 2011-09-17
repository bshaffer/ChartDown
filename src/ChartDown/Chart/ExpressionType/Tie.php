<?php

/**
* Tie Expression
*/
class ChartDown_Chart_ExpressionType_Tie implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return '~';
  }
  
  public function isChordExpression()
  {
    return false;
  }
  
  public function getName()
  {
    return 'tie';
  }
  
  public function getRegex()
  {
    return '~';
  }
}
