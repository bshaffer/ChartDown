<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Anticipation Expression
*/
class Anticipation implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '>';
    }

    public function getPosition()
    {
        return 'chord';
    }
  
    public function getName()
    {
        return 'anticipation';
    }

    public function getRegex()
    {
        return '>';
    }
}
