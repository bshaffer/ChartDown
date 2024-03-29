<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a text node.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_Node_Bar extends ChartDown_Node implements ChartDown_NodeOutputInterface
{
    /**
     * Compiles the node to PHP.
     *
     * @param ChartDown_Compiler A ChartDown_Compiler instance
     */
    public function compile(ChartDown_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("->addBar()\n")
            ->indent()
            ->subcompile(new ChartDown_Node($this->nodes))
            ->outdent()
            ->write("->end()\n")
        ;
    }
}
