<?php

namespace ChartDown\Chart\ExpressionType;

use ChartDown\Chart\Rhythm\RelativeMeterInterface;

/**
* Repeat Finish Expression
*/
class RepeatFinish implements ExpressionTypeInterface, RelativeMeterInterface
{
    public function getSymbol()
    {
        return ':}';
    }
    
    public function getPosition()
    {
        return 'bar';
    }
    
    public function getName()
    {
        return 'repeat finish';
    }
    
    public function getRegex()
    {
        return ':\}';
    }
    
    public function getRelativeMeter()
    {
        return 1;
    }
}