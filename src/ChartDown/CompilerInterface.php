<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Interface implemented by compiler classes.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
interface ChartDown_CompilerInterface
{
    /**
     * Compiles a node.
     *
     * @param  ChartDown_NodeInterface $node The node to compile
     *
     * @return ChartDown_CompilerInterface The current compiler instance
     */
    function compile(ChartDown_NodeInterface $node);

    /**
     * Gets the current PHP code after compilation.
     *
     * @return string The PHP code
     */
    function getSource();
}
