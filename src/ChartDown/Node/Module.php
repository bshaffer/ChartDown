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
 * Represents a module node.
 *
 * @package    chartdown
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Node_Module extends ChartDown_Node
{
    public function __construct(ChartDown_NodeInterface $body, ChartDown_Node_Expression $parent = null, ChartDown_NodeInterface $blocks, ChartDown_NodeInterface $macros, $filename)
    {
        parent::__construct(array('parent' => $parent, 'body' => $body, 'blocks' => $blocks, 'macros' => $macros), array('filename' => $filename), 1);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param ChartDown_Compiler A ChartDown_Compiler instance
     */
    public function compile(ChartDown_Compiler $compiler)
    {
        $this->compileChart($compiler);
    }

    protected function compileChart(ChartDown_Compiler $compiler)
    {
        $this->compileClassHeader($compiler);

        if (count($this->getNode('blocks'))) {
            $this->compileConstructor($compiler);
        }

        $this->compileGetParent($compiler);

        $this->compileSetupHeader($compiler);

        $this->compileSetupBody($compiler);

        $this->compileSetupFooter($compiler);

        $compiler->subcompile($this->getNode('blocks'));

        $this->compileMacros($compiler);

        $this->compileGetChartName($compiler);

        $this->compileClassFooter($compiler);
    }

    protected function compileGetParent(ChartDown_Compiler $compiler)
    {
        if (null === $this->getNode('parent')) {
            return;
        }

        $compiler
            ->write("public function getParent(array \$context)\n", "{\n")
            ->indent()
            ->write("if (null === \$this->parent) {\n")
            ->indent();
        ;

        if ($this->getNode('parent') instanceof ChartDown_Node_Expression_Constant) {
            $compiler
                ->write("\$this->parent = \$this->env->loadChart(")
                ->subcompile($this->getNode('parent'))
                ->raw(");\n")
            ;
        } else {
            $compiler
                ->write("\$this->parent = ")
                ->subcompile($this->getNode('parent'))
                ->raw(";\n")
                ->write("if (!\$this->parent")
                ->raw(" instanceof Chart) {\n")
                ->indent()
                ->write("\$this->parent = \$this->env->loadChart(\$this->parent);\n")
                ->outdent()
                ->write("}\n")
            ;
        }

        $compiler
            ->outdent()
            ->write("}\n\n")
            ->write("return \$this->parent;\n")
            ->outdent()
            ->write("}\n\n")
        ;
    }

    protected function compileSetupBody(ChartDown_Compiler $compiler)
    {
        if (null !== $this->getNode('parent')) {
            // remove all output nodes
            foreach ($this->getNode('body') as $node) {
                if (!$node instanceof ChartDown_NodeOutputInterface) {
                    $compiler->subcompile($node);
                }
            }

            $compiler
                ->write("\$this->getParent()->setup(\$context, \$this->blocks);\n")
            ;
        } else {
            $compiler->subcompile($this->getNode('body'));
        }
    }

    protected function compileClassHeader(ChartDown_Compiler $compiler)
    {
        $compiler
            ->write("<?php\n\n")
            // if the filename contains */, add a blank to avoid a PHP parse error
            ->write("/* ".str_replace('*/', '* /', $this->getAttribute('filename'))." */\n")
            ->write('class '.$compiler->getEnvironment()->getChartClass($this->getAttribute('filename')))
            ->raw(sprintf(" extends %s\n", $compiler->getEnvironment()->getBaseChartClass()))
            ->write("{\n")
            ->indent()
        ;

        if (null !== $this->getNode('parent')) {
            $compiler->write("protected \$parent;\n\n");
        }
    }

    protected function compileConstructor(ChartDown_Compiler $compiler)
    {
        $compiler
            ->write("public function __construct(\$options = array())\n", "{\n")
            ->indent()
            ->write("parent::__construct(\$options);\n\n")
            ->write("\$this->blocks = array(\n")
            ->indent()
        ;

        foreach ($this->getNode('blocks') as $name => $node) {
            $compiler
                ->write(sprintf("'%s' => array(\$this, 'block_%s'),\n", $name, $name))
            ;
        }

        $compiler
            ->outdent()
            ->write(");\n")
            ->write("\$this->setup();\n")
            ->outdent()
            ->write("}\n\n");
        ;
    }

    protected function compileSetupHeader(ChartDown_Compiler $compiler)
    {
        $compiler
            ->write("public function setup()\n", "{\n")
            ->indent()
        ;
    }

    protected function compileSetupFooter(ChartDown_Compiler $compiler)
    {
        $compiler
            ->outdent()
            ->write("}\n\n")
        ;
    }

    protected function compileClassFooter(ChartDown_Compiler $compiler)
    {
        $compiler
            ->outdent()
            ->write("}\n")
        ;
    }

    protected function compileMacros(ChartDown_Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('macros'));
    }

    protected function compileGetChartName(ChartDown_Compiler $compiler)
    {
        $compiler
            ->write("public function getChartName()\n", "{\n")
            ->indent()
            ->write('return ')
            ->repr($this->getAttribute('filename'))
            ->raw(";\n")
            ->outdent()
            ->write("}\n")
        ;
    }
}
