<?php

/**
* Coda Expression
*/
class ChartDown_Chart_ExpressionType_Coda implements ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol()
  {
    return '$';
  }
  
  public function isChordExpression()
  {
    return false;
  }
  
  public function getName()
  {
    return 'coda';
  }
  
  public function getRegex()
  {
    return '\$';
  }
}
