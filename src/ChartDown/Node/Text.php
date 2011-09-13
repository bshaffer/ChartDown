<?php

/**
 * Represents a text node.
 *
 * @package    chartdown
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Node_Text extends ChartDown_Node implements ChartDown_NodeOutputInterface
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
            ->write('->setText(')
            ->string($this->getAttribute('data'))
            ->raw(")\n")
        ;
    }
}
