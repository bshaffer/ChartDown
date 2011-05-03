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
 * Represents a text node.
 *
 * @package    chartdown
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Node_Metadata extends ChartDown_Node implements ChartDown_NodeOutputInterface
{
    public function __construct($name, $value, $lineno = -1)
    {
        parent::__construct(array(), array('name' => $name, 'value' => $value), $lineno);
    }
    /**
     * Compiles the node to PHP.
     *
     * @param ChartDown_Compiler A ChartDown_Compiler instance
     */
    public function compile(ChartDown_Compiler $compiler)
    {
        $name = $this->getAttribute('name');
        $value = $this->getAttribute('value');

        $compiler
          ->addDebugInfo($this)
        ;

        // For Key
        switch (strtolower($name)) {
          case 'time':
            $name = 'TimeSignature';
            break;
            
          case 'key_signature':
          case 'key signature':
            $name = 'Key';
            break;
          
          default:
            break;
        }

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("\$this->set%s(", str_replace(' ', '', ucwords(str_replace('_', ' ', $name)))))
        ;

        // For Value
        switch (strtolower($name)) {
          case 'time_signature':
          case 'time signature':
          
            $signature = explode('/', $value);
            
            if (count($signature) === 0) {
              $signature = array(4,4);
            }
            if (count($signature) === 1) {
              $signature[] = 4;
            }

            $value = sprintf('new ChartDown_Chart_TimeSignature(%s, %s)', $signature[0], $signature[1]);
            $compiler
              ->raw($value)
            ;

            break;

          case 'key':
          case 'key_signature':
          case 'key signature':

            $compiler
              ->raw('new ChartDown_Chart_Key(')
              ->string($value)
              ->raw(')')
            ;

            break;

          default:
            $compiler->string($value);
            break;
        }

        $compiler
            ->raw(");\n")
        ;
    }
}
