<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Repeat Start Expression
*/
class RepeatStart implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '{:';
    }
    
    public function getPosition()
    {
        return 'bar';
    }
    
    public function getName()
    {
        return 'repeat start';
    }
    
    public function getRegex()
    {
        return '\{:';
    }
}
