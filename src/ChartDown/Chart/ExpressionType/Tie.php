<?php

namespace ChartDown\Chart\ExpressionType;

use ChartDown\Chart\Rhythm\RelativeMeterInterface;

/**
* Tie Expression
*/
class Tie implements ExpressionTypeInterface, RelativeMeterInterface
{
    public function getSymbol()
    {
        return '~';
    }
    
    public function getPosition()
    {
        return 'chord';
    }
    
    public function getName()
    {
        return 'tie';
    }
    
    public function getRegex()
    {
        return '~';
    }
    
    public function getRelativeMeter()
    {
        return 0;
    }
}
