<?php

namespace ChartDown\Chart\ExpressionType;

/**
* Diamond Expression
*/
class Diamond implements ExpressionTypeInterface
{
    public function getSymbol()
    {
        return '*';
    }

    public function getPosition()
    {
        return 'chord';
    }
    
    public function getName()
    {
        return 'diamond';
    }

    public function getRegex()
    {
        return '\*';
    }
}
