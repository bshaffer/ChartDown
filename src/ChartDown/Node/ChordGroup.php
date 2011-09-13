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
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_Node_ChordGroup extends ChartDown_Node implements ChartDown_NodeOutputInterface
{
    public function __construct($nodes = array(), $lineno)
    {
        parent::__construct($nodes, array(), $lineno);
    }

    public function addChordNode(ChartDown_Node_Chord $chordNode)
    {
        $this->nodes[] = $chordNode;
    }

    public function getNumChords()
    {
        return count($this->nodes);
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
            ->write("->addChordGroup()\n")
            ->indent()
                ->subcompile(new ChartDown_Node($this->nodes, array(), $this->lineno))
            ->outdent()
            ->write("->end()\n")
        ;
    }
}
