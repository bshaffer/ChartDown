<?php

namespace ChartDown\Chart\ExpressionType;

use ChartDown\Chart\Rhythm\RelativeMeterInterface;

/**
* Coda Expression
*/
class Coda implements ExpressionTypeInterface, RelativeMeterInterface
{
    public function getSymbol()
    {
        return '$';
    }

    public function getPosition()
    {
        return 'top';
    }
    
    public function getName()
    {
        return 'coda';
    }

    public function getRegex()
    {
        return '\$';
    }
    
    public function getRelativeMeter()
    {
        return 0;
    }
}
