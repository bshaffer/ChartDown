<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Repeat Bar Expression
*/
class RepeatBar implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '%';
    }
    
    public function getPosition()
    {
        return 'bar';
    }

    public function getName()
    {
        return 'repeat bar';
    }

    public function getRegex()
    {
        return '%';
    }
}
