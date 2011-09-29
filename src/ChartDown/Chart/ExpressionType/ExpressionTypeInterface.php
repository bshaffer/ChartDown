<?php

namespace ChartDown\Chart\ExpressionType;

interface ExpressionTypeInterface
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