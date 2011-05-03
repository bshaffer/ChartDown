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
 * Compiles a node to PHP code.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_Compiler implements ChartDown_CompilerInterface
{
    protected $lastLine;
    protected $source;
    protected $indentation;
    protected $env;

    /**
     * Constructor.
     *
     * @param ChartDown_Environment $env The chartdown environment instance
     */
    public function __construct(ChartDown_Environment $env)
    {
        $this->env = $env;
    }

    /**
     * Returns the environment instance related to this compiler.
     *
     * @return ChartDown_Environment The environment instance
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * Gets the current PHP code after compilation.
     *
     * @return string The PHP code
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Compiles a node.
     *
     * @param ChartDown_NodeInterface $node   The node to compile
     * @param integer            $indent The current indentation
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function compile(ChartDown_NodeInterface $node, $indentation = 0)
    {
        $this->lastLine = null;
        $this->source = '';
        $this->indentation = $indentation;

        $node->compile($this);

        return $this;
    }

    public function subcompile(ChartDown_NodeInterface $node, $raw = true)
    {
        if (false === $raw)
        {
            $this->addIndentation();
        }

        $node->compile($this);

        return $this;
    }

    /**
     * Adds a raw string to the compiled code.
     *
     * @param  string $string The string
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function raw($string)
    {
        $this->source .= $string;

        return $this;
    }

    /**
     * Writes a string to the compiled code by adding indentation.
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function write()
    {
        $strings = func_get_args();
        foreach ($strings as $string) {
            $this->addIndentation();
            $this->source .= $string;
        }

        return $this;
    }

    public function addIndentation()
    {
        $this->source .= str_repeat(' ', $this->indentation * 4);

        return $this;
    }

    /**
     * Adds a quoted string to the compiled code.
     *
     * @param  string $string The string
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function string($value)
    {
        $this->source .= sprintf('"%s"', addcslashes($value, "\t\"\$\\"));

        return $this;
    }

    /**
     * Returns a PHP representation of a given value.
     *
     * @param  mixed $value The value to convert
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function repr($value)
    {
        if (is_int($value) || is_float($value)) {
            $this->raw($value);
        } else if (null === $value) {
            $this->raw('null');
        } else if (is_bool($value)) {
            $this->raw($value ? 'true' : 'false');
        } else if (is_array($value)) {
            $this->raw('array(');
            $i = 0;
            foreach ($value as $key => $value) {
                if ($i++) {
                    $this->raw(', ');
                }
                $this->repr($key);
                $this->raw(' => ');
                $this->repr($value);
            }
            $this->raw(')');
        } else {
            $this->string($value);
        }

        return $this;
    }

    /**
     * Adds debugging information.
     *
     * @param ChartDown_NodeInterface $node The related chartdown node
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function addDebugInfo(ChartDown_NodeInterface $node)
    {
        if ($node->getLine() != $this->lastLine) {
            $this->lastLine = $node->getLine();
            $this->write("// line {$node->getLine()}\n");
        }

        return $this;
    }

    /**
     * Indents the generated code.
     *
     * @param integer $indent The number of indentation to add
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function indent($step = 1)
    {
        $this->indentation += $step;

        return $this;
    }

    /**
     * Outdents the generated code.
     *
     * @param integer $indent The number of indentation to remove
     *
     * @return ChartDown_Compiler The current compiler instance
     */
    public function outdent($step = 1)
    {
        $this->indentation -= $step;

        if ($this->indentation < 0) {
            throw new ChartDown_Error('Unable to call outdent() as the indentation would become negative');
        }

        return $this;
    }
}
