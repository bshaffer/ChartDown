<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2011 Brent Shaffer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a chart expression.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
abstract class ChartDown_Chart_Expression implements ChartDown_Chart_ExpressionInterface
{
    protected $value;

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
