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
 * ChartDown_NodeTraverser is a node traverser.
 *
 * It visits all nodes and their children and call the given visitor for each.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_NodeTraverser
{
    protected $env;
    protected $visitors;

    /**
     * Constructor.
     *
     * @param ChartDown_Environment $env      A ChartDown_Environment instance
     * @param array            $visitors An array of ChartDown_NodeVisitorInterface instances
     */
    public function __construct(ChartDown_Environment $env, array $visitors = array())
    {
        $this->env = $env;
        $this->visitors = array();
        foreach ($visitors as $visitor) {
            $this->addVisitor($visitor);
        }
    }

    /**
     * Adds a visitor.
     *
     * @param ChartDown_NodeVisitorInterface $visitor A ChartDown_NodeVisitorInterface instance
     */
    public function addVisitor(ChartDown_NodeVisitorInterface $visitor)
    {
        if (!isset($this->visitors[$visitor->getPriority()])) {
            $this->visitors[$visitor->getPriority()] = array();
        }

        $this->visitors[$visitor->getPriority()][] = $visitor;
    }

    /**
     * Traverses a node and calls the registered visitors.
     *
     * @param ChartDown_NodeInterface $node A ChartDown_NodeInterface instance
     */
    public function traverse(ChartDown_NodeInterface $node)
    {
        ksort($this->visitors);
        foreach ($this->visitors as $visitors) {
            foreach ($visitors as $visitor) {
                $node = $this->traverseForVisitor($visitor, $node);
            }
        }

        return $node;
    }

    protected function traverseForVisitor(ChartDown_NodeVisitorInterface $visitor, ChartDown_NodeInterface $node = null)
    {
        if (null === $node) {
            return null;
        }

        $node = $visitor->enterNode($node, $this->env);

        foreach ($node as $k => $n) {
            if (false !== $n = $this->traverseForVisitor($visitor, $n)) {
                $node->setNode($k, $n);
            } else {
                $node->removeNode($k);
            }
        }

        return $visitor->leaveNode($node, $this->env);
    }
}
