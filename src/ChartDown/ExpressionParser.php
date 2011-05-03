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
 * Parses expressions.
 *
 * This parser implements a "Precedence climbing" algorithm.
 *
 * @see http://www.engr.mun.ca/~theo/Misc/exp_parsing.htm
 * @see http://en.wikipedia.org/wiki/Operator-precedence_parser
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_ExpressionParser
{
    const OPERATOR_LEFT = 1;
    const OPERATOR_RIGHT = 2;

    protected $parser;
    protected $unaryOperators;
    protected $binaryOperators;

    public function __construct(ChartDown_Parser $parser, array $unaryOperators, array $binaryOperators)
    {
        $this->parser = $parser;
        $this->unaryOperators = $unaryOperators;
        $this->binaryOperators = $binaryOperators;
    }

    public function parseExpression($precedence = 0)
    {
        $expr = $this->getPrimary();
        $token = $this->parser->getCurrentToken();
        while ($this->isBinary($token) && $this->binaryOperators[$token->getValue()]['precedence'] >= $precedence) {
            $op = $this->binaryOperators[$token->getValue()];
            $this->parser->getStream()->next();

            if (isset($op['callable'])) {
                $expr = call_user_func($op['callable'], $this->parser, $expr);
            } else {
                $expr1 = $this->parseExpression(self::OPERATOR_LEFT === $op['associativity'] ? $op['precedence'] + 1 : $op['precedence']);
                $class = $op['class'];
                $expr = new $class($expr, $expr1, $token->getLine());
            }

            $token = $this->parser->getCurrentToken();
        }

        if (0 === $precedence) {
            return $this->parseConditionalExpression($expr);
        }

        return $expr;
    }

    protected function getPrimary()
    {
        $token = $this->parser->getCurrentToken();

        if ($this->isUnary($token)) {
            $operator = $this->unaryOperators[$token->getValue()];
            $this->parser->getStream()->next();
            $expr = $this->parseExpression($operator['precedence']);
            $class = $operator['class'];

            return $this->parsePostfixExpression(new $class($expr, $token->getLine()));
        } elseif ($token->test(ChartDown_Token::PUNCTUATION_TYPE, '(')) {
            $this->parser->getStream()->next();
            $expr = $this->parseExpression();
            $this->parser->getStream()->expect(ChartDown_Token::PUNCTUATION_TYPE, ')', 'An opened parenthesis is not properly closed');

            return $this->parsePostfixExpression($expr);
        }

        return $this->parsePrimaryExpression();
    }

    protected function parseConditionalExpression($expr)
    {
        while ($this->parser->getStream()->test(ChartDown_Token::PUNCTUATION_TYPE, '?')) {
            $this->parser->getStream()->next();
            $expr2 = $this->parseExpression();
            $this->parser->getStream()->expect(ChartDown_Token::PUNCTUATION_TYPE, ':', 'The ternary operator must have a default value');
            $expr3 = $this->parseExpression();

            $expr = new ChartDown_Node_Expression_Conditional($expr, $expr2, $expr3, $this->parser->getCurrentToken()->getLine());
        }

        return $expr;
    }

    protected function isUnary(ChartDown_Token $token)
    {
        return $token->test(ChartDown_Token::OPERATOR_TYPE) && isset($this->unaryOperators[$token->getValue()]);
    }

    protected function isBinary(ChartDown_Token $token)
    {
        return $token->test(ChartDown_Token::OPERATOR_TYPE) && isset($this->binaryOperators[$token->getValue()]);
    }

    public function parsePrimaryExpression()
    {
        $token = $this->parser->getCurrentToken();
        switch ($token->getType()) {
            case ChartDown_Token::NAME_TYPE:
                $this->parser->getStream()->next();
                switch ($token->getValue()) {
                    case 'true':
                        $node = new ChartDown_Node_Expression_Constant(true, $token->getLine());
                        break;

                    case 'false':
                        $node = new ChartDown_Node_Expression_Constant(false, $token->getLine());
                        break;

                    case 'none':
                        $node = new ChartDown_Node_Expression_Constant(null, $token->getLine());
                        break;

                    default:
                        $node = new ChartDown_Node_Expression_Name($token->getValue(), $token->getLine());
                }
                break;

            case ChartDown_Token::NUMBER_TYPE:
            case ChartDown_Token::STRING_TYPE:
                $this->parser->getStream()->next();
                $node = new ChartDown_Node_Expression_Constant($token->getValue(), $token->getLine());
                break;

            default:
                if ($token->test(ChartDown_Token::PUNCTUATION_TYPE, '[')) {
                    $node = $this->parseArrayExpression();
                } elseif ($token->test(ChartDown_Token::PUNCTUATION_TYPE, '{')) {
                    $node = $this->parseHashExpression();
                } else {
                    throw new ChartDown_Error_Syntax(sprintf('Unexpected token "%s" of value "%s"', ChartDown_Token::typeToEnglish($token->getType(), $token->getLine()), $token->getValue()), $token->getLine());
                }
        }

        return $this->parsePostfixExpression($node);
    }

    public function parseArrayExpression()
    {
        $stream = $this->parser->getStream();
        $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, '[', 'An array element was expected');
        $elements = array();
        while (!$stream->test(ChartDown_Token::PUNCTUATION_TYPE, ']')) {
            if (!empty($elements)) {
                $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, ',', 'An array element must be followed by a comma');

                // trailing ,?
                if ($stream->test(ChartDown_Token::PUNCTUATION_TYPE, ']')) {
                    break;
                }
            }

            $elements[] = $this->parseExpression();
        }
        $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, ']', 'An opened array is not properly closed');

        return new ChartDown_Node_Expression_Array($elements, $stream->getCurrent()->getLine());
    }

    public function parseHashExpression()
    {
        $stream = $this->parser->getStream();
        $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, '{', 'A hash element was expected');
        $elements = array();
        while (!$stream->test(ChartDown_Token::PUNCTUATION_TYPE, '}')) {
            if (!empty($elements)) {
                $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, ',', 'A hash value must be followed by a comma');

                // trailing ,?
                if ($stream->test(ChartDown_Token::PUNCTUATION_TYPE, '}')) {
                    break;
                }
            }

            if (!$stream->test(ChartDown_Token::STRING_TYPE) && !$stream->test(ChartDown_Token::NUMBER_TYPE)) {
                $current = $stream->getCurrent();
                throw new ChartDown_Error_Syntax(sprintf('A hash key must be a quoted string or a number (unexpected token "%s" of value "%s"', ChartDown_Token::typeToEnglish($current->getType(), $current->getLine()), $current->getValue()), $current->getLine());
            }

            $key = $stream->next()->getValue();
            $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, ':', 'A hash key must be followed by a colon (:)');
            $elements[$key] = $this->parseExpression();
        }
        $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, '}', 'An opened hash is not properly closed');

        return new ChartDown_Node_Expression_Array($elements, $stream->getCurrent()->getLine());
    }

    public function parsePostfixExpression($node)
    {
        $firstPass = true;
        while (true) {
            $token = $this->parser->getCurrentToken();
            if ($token->getType() == ChartDown_Token::PUNCTUATION_TYPE) {
                if ('.' == $token->getValue() || '[' == $token->getValue()) {
                    $node = $this->parseSubscriptExpression($node);
                } elseif ('|' == $token->getValue()) {
                    $node = $this->parseFilterExpression($node);
                } elseif ($firstPass && $node instanceof ChartDown_Node_Expression_Name && '(' == $token->getValue()) {
                    $node = $this->getFunctionNode($node);
                } else {
                    break;
                }
            } else {
                break;
            }

            $firstPass = false;
        }

        return $node;
    }

    public function getFunctionNode(ChartDown_Node_Expression_Name $node)
    {
        $args = $this->parseArguments();

        if ('parent' === $node->getAttribute('name')) {
            if (!count($this->parser->getBlockStack())) {
                throw new ChartDown_Error_Syntax('Calling "parent" outside a block is forbidden', $node->getLine());
            }

            if (!$this->parser->getParent()) {
                throw new ChartDown_Error_Syntax('Calling "parent" on a template that does not extend another one is forbidden', $node->getLine());
            }

            return new ChartDown_Node_Expression_Parent($this->parser->peekBlockStack(), $node->getLine());
        }

        if ('block' === $node->getAttribute('name')) {
            return new ChartDown_Node_Expression_BlockReference($args->getNode(0), false, $node->getLine());
        }

        if (null !== $alias = $this->parser->getImportedFunction($node->getAttribute('name'))) {
            return new ChartDown_Node_Expression_GetAttr($alias['node'], new ChartDown_Node_Expression_Constant($alias['name'], $node->getLine()), $args, ChartDown_TemplateInterface::METHOD_CALL, $node->getLine());
        }

        return new ChartDown_Node_Expression_Function($node, $args, $node->getLine());
    }

    public function parseSubscriptExpression($node)
    {
        $token = $this->parser->getStream()->next();
        $lineno = $token->getLine();
        $arguments = new ChartDown_Node();
        $type = ChartDown_TemplateInterface::ANY_CALL;
        if ($token->getValue() == '.') {
            $token = $this->parser->getStream()->next();
            if (
                $token->getType() == ChartDown_Token::NAME_TYPE
                ||
                $token->getType() == ChartDown_Token::NUMBER_TYPE
                ||
                ($token->getType() == ChartDown_Token::OPERATOR_TYPE && preg_match(ChartDown_Lexer::REGEX_NAME, $token->getValue()))
            ) {
                $arg = new ChartDown_Node_Expression_Constant($token->getValue(), $lineno);

                if ($this->parser->getStream()->test(ChartDown_Token::PUNCTUATION_TYPE, '(')) {
                    $type = ChartDown_TemplateInterface::METHOD_CALL;
                    $arguments = $this->parseArguments();
                } else {
                    $arguments = new ChartDown_Node();
                }
            } else {
                throw new ChartDown_Error_Syntax('Expected name or number', $lineno);
            }
        } else {
            $type = ChartDown_TemplateInterface::ARRAY_CALL;

            $arg = $this->parseExpression();
            $this->parser->getStream()->expect(ChartDown_Token::PUNCTUATION_TYPE, ']');
        }

        return new ChartDown_Node_Expression_GetAttr($node, $arg, $arguments, $type, $lineno);
    }

    public function parseFilterExpression($node)
    {
        $this->parser->getStream()->next();

        return $this->parseFilterExpressionRaw($node);
    }

    public function parseFilterExpressionRaw($node, $tag = null)
    {
        while (true) {
            $token = $this->parser->getStream()->expect(ChartDown_Token::NAME_TYPE);

            $name = new ChartDown_Node_Expression_Constant($token->getValue(), $token->getLine());
            if (!$this->parser->getStream()->test(ChartDown_Token::PUNCTUATION_TYPE, '(')) {
                $arguments = new ChartDown_Node();
            } else {
                $arguments = $this->parseArguments();
            }

            $node = new ChartDown_Node_Expression_Filter($node, $name, $arguments, $token->getLine(), $tag);

            if (!$this->parser->getStream()->test(ChartDown_Token::PUNCTUATION_TYPE, '|')) {
                break;
            }

            $this->parser->getStream()->next();
        }

        return $node;
    }

    public function parseArguments()
    {
        $args = array();
        $stream = $this->parser->getStream();

        $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, '(', 'A list of arguments must be opened by a parenthesis');
        while (!$stream->test(ChartDown_Token::PUNCTUATION_TYPE, ')')) {
            if (!empty($args)) {
                $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, ',', 'Arguments must be separated by a comma');
            }
            $args[] = $this->parseExpression();
        }
        $stream->expect(ChartDown_Token::PUNCTUATION_TYPE, ')', 'A list of arguments must be closed by a parenthesis');

        return new ChartDown_Node($args);
    }

    public function parseAssignmentExpression()
    {
        $targets = array();
        while (true) {
            $token = $this->parser->getStream()->expect(ChartDown_Token::NAME_TYPE, null, 'Only variables can be assigned to');
            if (in_array($token->getValue(), array('true', 'false', 'none'))) {
                throw new ChartDown_Error_Syntax(sprintf('You cannot assign a value to "%s"', $token->getValue()), $token->getLine());
            }
            $targets[] = new ChartDown_Node_Expression_AssignName($token->getValue(), $token->getLine());

            if (!$this->parser->getStream()->test(ChartDown_Token::PUNCTUATION_TYPE, ',')) {
                break;
            }
            $this->parser->getStream()->next();
        }

        return new ChartDown_Node($targets);
    }

    public function parseMultitargetExpression()
    {
        $targets = array();
        while (true) {
            $targets[] = $this->parseExpression();
            if (!$this->parser->getStream()->test(ChartDown_Token::PUNCTUATION_TYPE, ',')) {
                break;
            }
            $this->parser->getStream()->next();
        }

        return new ChartDown_Node($targets);
    }
}
