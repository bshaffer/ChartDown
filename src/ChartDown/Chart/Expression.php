<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2011 Brent Shaffer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChartDown\Chart;

use ChartDown\Chart\Rhythm\RelativeMeterInterface;
use ChartDown\Chart\Expression\ExpressionTypeInterface;

/**
 * Represents a chart expression.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class Expression implements RelativeMeterInterface
{
    protected $value;
    protected $type;

    const ACCENT        = 1;
    const ANTICIPATION  = 2;
    const CODA          = 3;
    const DIAMOND       = 4;
    const FERMATA       = 5;
    const REPEAT_BAR    = 6;
    const REPEAT_ENDING = 7;
    const REPEAT_FINISH = 8;
    const SEGNO         = 9;
    const TENUDO        = 10;
    const TIE           = 11;
    
    public function __construct(ExpressionTypeInterface $type, $value = null)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setType(ExpressionTypeInterface $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
    
    public function getName()
    {
        return is_null($this->type) ? '' : $this->type->getName();
    }
    
    public function getPosition()
    {
        if (!$this->type) {
            throw new Exception('Must set expression type to call this method');
        }
        return $this->type->getPosition();
    }
    
    public function getRelativeMeter()
    {
        if (!$this->type) {
            throw new Exception('Must set expression type to call this method');
        }
        return $this->type->getRelativeMeter();
    }
}
