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
class ChartDown_Node_Expression extends ChartDown_Node implements ChartDown_NodeOutputInterface
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
        $env = $compiler->getEnvironment();
        $expression = null;
        $value      = null;

        foreach ($env->getExpressionTypes() as $type) {
            if (1 == preg_match(sprintf('/%s/', $type->getRegex()), $this->getAttribute('data'), $matches)) {
                $expression = $type;
                $value = isset($matches[1]) ? $matches[1] : null;
            }
        }
        
        if (is_null($expression)) {
            throw new Exception(sprintf('Expression "%s" did not match a valid expression type', $this->getAttribute('data')));
        }

        $compiler
            ->addDebugInfo($this)
            ->write('->addExpression(new Expression(')
            ->raw(sprintf('new %s(), ', get_class($expression)))
            ->raw(var_export($value, true))
            ->raw("))\n")
        ;
    }
}
