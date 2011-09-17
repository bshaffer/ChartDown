<?php

interface ChartDown_Chart_ExpressionTypeInterface
{
  public function getSymbol();
  public function getRegex();
  public function getName();
  public function isChordExpression();
}