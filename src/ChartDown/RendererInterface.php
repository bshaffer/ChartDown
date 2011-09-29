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
 * Interface implemented by token parsers.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
interface ChartDown_RendererInterface
{
    /**
     * Renders the chart to the appropriate medium
     *
     * @param mixed - a ChartDown\Chart\Chart instance to render, or other medium (ex: HTML for PDF Renderer)
     */
    function render($chart);
}
