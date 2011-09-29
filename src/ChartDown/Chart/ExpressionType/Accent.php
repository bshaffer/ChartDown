<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Accent Expression
*/
class Accent implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '^';
    }
  
    public function getPosition()
    {
        return 'chord';
    }
  
    public function getName()
    {
        return 'accent';
    }

    public function getRegex()
    {
        return '\^';
    }
}
