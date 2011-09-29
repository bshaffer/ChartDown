<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Fermata Expression
*/
class Fermata implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '!';
    }

    public function getPosition()
    {
        return 'chord';
    }

    public function getName()
    {
        return 'fermata';
    }

    public function getRegex()
    {
        return '!';
    }
}
