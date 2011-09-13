<?php

interface ChartDown_Chart_ExpressionInterface
{
  public function getSymbol();
  public function getRegex();
  public function getEnglishName();
  public function getValue();
  public function setValue();
  public function isChordExpression();
  public function isPreChordExpression();
}