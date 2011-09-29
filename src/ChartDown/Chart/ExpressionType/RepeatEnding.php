<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Repeat Ending Expression
*/
class RepeatEnding implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '{num}';
    }

    public function getPosition()
    {
        return 'bar-top';
    }

    public function getName()
    {
        return 'repeat ending';
    }

    public function getRegex()
    {
        return '\{(\d+)\}';
    }
}
