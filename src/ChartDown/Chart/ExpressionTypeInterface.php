<?php

interface ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol();
  public function getRegex();
  public function getName();

  /**
   * returns the position of the expression
   *
   * @return string - one of "top", "bar", "chord"
   */
  public function getPosition();
}