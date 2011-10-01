<?php

/*
 * This file is part of ChartDown.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a text node.
 *
 * @package    chartdown
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Node_Row extends ChartDown_Node implements ChartDown_NodeOutputInterface
{
    public function __construct($data, $lineno)
    {
        parent::__construct(array(), array('data' => $data), $lineno);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param ChartDown_Compiler A ChartDown_Compiler instance
     */
    public function compile(ChartDown_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("\$this->addRow()\n")
            ->indent()
            ->subcompile(new ChartDown_Node($this->nodes))
            ->outdent()
            ->write("->end();\n")
        ;
    }
}
