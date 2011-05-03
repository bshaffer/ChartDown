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
 * Lexes a template string.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
abstract class ChartDown_Lexer_Base implements ChartDown_LexerInterface
{
    protected $tokens;
    protected $code;
    protected $cursor;
    protected $lineno;
    protected $end;
    protected $state;
    protected $brackets;

    protected $env;
    protected $filename;
    protected $options;
    protected $operatorRegex;

    public function __construct(ChartDown_Environment $env, array $options = array())
    {
        $this->env     = $env;
        $this->options = $options;
    }

    /**
     * Tokenizes a source code.
     *
     * @param  string $code     The source code
     * @param  string $filename A unique identifier for the source code
     *
     * @return ChartDown_TokenStream A token stream instance
     */
    public function tokenize($code, $filename = null)
    {
        if (function_exists('mb_internal_encoding') && ((int) ini_get('mbstring.func_overload')) & 2) {
            $mbEncoding = mb_internal_encoding();
            mb_internal_encoding('ASCII');
        }

        $this->code = str_replace(array("\r\n", "\r"), "\n", $code);
        $this->filename = $filename;
        $this->cursor = 0;
        $this->lineno = 1;
        $this->end = strlen($this->code);
        $this->tokens = array();
        $this->brackets = array();
        
        $this->doTokenize($code);
        
        if (!empty($this->brackets)) {
            list($expect, $lineno) = array_pop($this->brackets);
            throw new ChartDown_Error_Syntax(sprintf('Unclosed "%s"', $expect), $lineno, $this->filename);
        }

        if (isset($mbEncoding)) {
            mb_internal_encoding($mbEncoding);
        }

        return new ChartDown_TokenStream($this->tokens, $this->filename);
    }
    
    abstract function doTokenize($code);

    protected function pushToken($type, $value = '')
    {
        $this->tokens[] = new ChartDown_Token($type, $value, $this->lineno);
    }

    protected function moveCursor($text)
    {
        $this->cursor += strlen($text);
        $this->lineno += substr_count($text, "\n");
    }

    protected function getOperatorRegex()
    {
        if (null !== $this->operatorRegex) {
            return $this->operatorRegex;
        }

        $operators = array_merge(
            array('='),
            array_keys($this->env->getUnaryOperators()),
            array_keys($this->env->getBinaryOperators())
        );

        $operators = array_combine($operators, array_map('strlen', $operators));
        arsort($operators);

        $regex = array();
        foreach ($operators as $operator => $length) {
            // an operator that ends with a character must be followed by
            // a whitespace or a parenthesis
            if (ctype_alpha($operator[$length - 1])) {
                $regex[] = preg_quote($operator, '/').'(?=[ ()])';
            } else {
                $regex[] = preg_quote($operator, '/');
            }
        }

        return $this->operatorRegex = '/'.implode('|', $regex).'/A';
    }
}
