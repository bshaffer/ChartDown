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
 * Represents a chart label.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Chart_Label
{
  protected $label;
  protected $options;

  const SHAPE_SQUARE = 1;
  const SHAPE_CIRCLE = 2;

  public function __construct($label, $options = array())
  {
    $this->label = $label;
    $this->options = array_merge(array(
      'border'  => false,
      'shape'   => self::SHAPE_SQUARE,
    ), $options);
  }

  public function __toString()
  {
    $label = (string) $this->label;

    if ($this->options['border']) {
      switch ($this->options['shape']) {
        case self::SHAPE_SQUARE:
          $label = sprintf('[%s]', $label);
          break;

        case self::SHAPE_CIRCLE:
          $label = sprintf('(%s)', $label);
          break;

        default:
          throw new ChartDown_Runtime_Exception('Unsupported shape for label: "%s" (label text: "%s")', $this->options['shape'], $label);
      }
    }

    return $label;
  }
}
