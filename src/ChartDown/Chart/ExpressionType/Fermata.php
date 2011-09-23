<?php

/**
* Fermata Expression
*/
class ChartDown_Chart_ExpressionType_Fermata implements ChartDown_Chart_ExpressionTypeInterface
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
