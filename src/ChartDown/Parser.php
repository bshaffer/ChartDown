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
 * Default parser implementation.
 *
 * @package chartdown
 * @author  Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_Parser implements ChartDown_ParserInterface
{
    protected $stream;
    protected $parent;
    protected $handlers;
    protected $visitors;
    protected $expressionParser;
    protected $blocks;
    protected $blockStack;
    protected $macros;
    protected $env;
    protected $reservedMacroNames;
    protected $importedFunctions;
    protected $tmpVarCount;

    /**
     * Constructor.
     *
     * @param ChartDown_Environment $env A ChartDown_Environment instance
     */
    public function __construct(ChartDown_Environment $env)
    {
        $this->env = $env;
    }

    public function getVarName()
    {
        return sprintf('__internal_%s_%d', substr($this->env->getTemplateClass($this->stream->getFilename()), strlen($this->env->getTemplateClassPrefix())), ++$this->tmpVarCount);
    }

    /**
     * Converts a token stream to a node tree.
     *
     * @param  ChartDown_TokenStream $stream A token stream instance
     *
     * @return ChartDown_Node_Module A node tree
     */
    public function parse(ChartDown_TokenStream $stream)
    {
        $this->tmpVarCount = 0;

        // tag handlers
        $this->handlers = $this->env->getTokenParsers();
        $this->handlers->setParser($this);

        // node visitors
        $this->visitors = $this->env->getNodeVisitors();

        if (null === $this->expressionParser) {
            $this->expressionParser = new ChartDown_ExpressionParser($this, $this->env->getUnaryOperators(), $this->env->getBinaryOperators());
        }

        $this->stream = $stream;
        $this->parent = null;
        $this->blocks = array();
        $this->macros = array();
        $this->blockStack = array();
        $this->importedFunctions = array(array());

        try {
            $body = $this->subparse(null);

            if (null !== $this->parent) {
                $this->checkBodyNodes($body);
            }
        } catch (ChartDown_Error_Syntax $e) {
            if (null === $e->getTemplateFile()) {
                $e->setTemplateFile($this->stream->getFilename());
            }

            throw $e;
        }

        $node = new ChartDown_Node_Module($body, $this->parent, new ChartDown_Node($this->blocks), new ChartDown_Node($this->macros), $this->stream->getFilename());

        $traverser = new ChartDown_NodeTraverser($this->env, $this->visitors);

        return $traverser->traverse($node);
    }

    public function subparse($test, $dropNeedle = false)
    {
        $lineno = $this->getCurrentToken()->getLine();
        $rows = array();
        $bar = array();
        $barTmp = null;

        while (!$this->stream->isEOF()) {
            switch ($this->getCurrentToken()->getType()) {
                case ChartDown_Token::LINE_START:
                    $token = $this->stream->next();
                    $row = new ChartDown_Node_Row($token->getValue(), $token->getLine());
                    $rows[] = $row;
                    break;

                case ChartDown_Token::LINE_END:
                    $row->addNode(new ChartDown_Node_Bar($bar));
                    $bar = array();
                    $this->stream->next();
                    break;

                case ChartDown_Token::CHORD_TYPE:
                    $token = $this->stream->next();
                    $bar[] = new ChartDown_Node_Chord($token->getValue(), $token->getLine());
                    break;

                case ChartDown_Token::CHORD_GROUP_START_TYPE:
                    $token  = $this->stream->next();
                    $barTmp = $bar;
                    $bar    = array();
                    break;

                case ChartDown_Token::CHORD_GROUP_END_TYPE:
                    $barTmp[] = new ChartDown_Node_ChordGroup($bar, $token->getLine());
                    $bar = $barTmp;
                    $this->stream->next();
                    break;

                case ChartDown_Token::RHYTHM_TYPE:
                    $token = $this->stream->next();
                    $bar[] = new ChartDown_Node_Rhythm($token->getValue(), $token->getLine());
                    break;

                case ChartDown_Token::EXPRESSION_TYPE:
                    $token = $this->stream->next();
                    $bar[] = new ChartDown_Node_Expression($token->getValue(), $token->getLine());
                    break;

                case ChartDown_Token::TEXT_TYPE:
                    $token = $this->stream->next();
                    $bar[] = new ChartDown_Node_Text($token->getValue(), $token->getLine());
                    break;

                case ChartDown_Token::METADATA_KEY_TYPE:
                    $key = $this->stream->next();
                    $value = $this->stream->expect(ChartDown_Token::METADATA_VALUE_TYPE);
                    $rows[] = new ChartDown_Node_Metadata($key->getValue(), $value->getValue(), $key->getLine());
                    break;

                case ChartDown_Token::BAR_LINE:
                    $row->addNode(new ChartDown_Node_Bar($bar));
                    $bar = array();
                    $this->stream->next();
                    break;

                case ChartDown_Token::END_ROW_TYPE:
                    $rows[] = new ChartDown_Node_RowBreak();
                    $this->stream->next();
                    break;

                default:
                    throw new ChartDown_Error_Syntax('Lexer or parser ended up in unsupported state.');
            }
        }

        return new ChartDown_Node($rows, array(), $lineno);
    }

    public function addHandler($name, $class)
    {
        $this->handlers[$name] = $class;
    }

    public function addNodeVisitor(ChartDown_NodeVisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;
    }

    public function getBlockStack()
    {
        return $this->blockStack;
    }

    public function peekBlockStack()
    {
        return $this->blockStack[count($this->blockStack) - 1];
    }

    public function popBlockStack()
    {
        array_pop($this->blockStack);
    }

    public function pushBlockStack($name)
    {
        $this->blockStack[] = $name;
    }

    public function hasBlock($name)
    {
        return isset($this->blocks[$name]);
    }

    public function setBlock($name, $value)
    {
        $this->blocks[$name] = $value;
    }

    public function hasMacro($name)
    {
        return isset($this->macros[$name]);
    }

    public function setMacro($name, ChartDown_Node_Macro $node)
    {
        if (null === $this->reservedMacroNames) {
            $this->reservedMacroNames = array();
            $r = new ReflectionClass($this->env->getBaseTemplateClass());
            foreach ($r->getMethods() as $method) {
                $this->reservedMacroNames[] = $method->getName();
            }
        }

        if (in_array($name, $this->reservedMacroNames)) {
            throw new ChartDown_Error_Syntax(sprintf('"%s" cannot be used as a macro name as it is a reserved keyword', $name), $node->getLine());
        }

        $this->macros[$name] = $node;
    }

    public function addImportedFunction($alias, $name, ChartDown_Node_Expression $node)
    {
        $this->importedFunctions[0][$alias] = array('name' => $name, 'node' => $node);
    }

    public function getImportedFunction($alias)
    {
        foreach ($this->importedFunctions as $functions) {
            if (isset($functions[$alias])) {
                return $functions[$alias];
            }
        }
    }

    public function pushLocalScope()
    {
        array_unshift($this->importedFunctions, array());
    }

    public function popLocalScope()
    {
        array_shift($this->importedFunctions);
    }

    /**
     * Gets the expression parser.
     *
     * @return ChartDown_ExpressionParser The expression parser
     */
    public function getExpressionParser()
    {
        return $this->expressionParser;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Gets the token stream.
     *
     * @return ChartDown_TokenStream The token stream
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Gets the current token.
     *
     * @return ChartDown_Token The current token
     */
    public function getCurrentToken()
    {
        return $this->stream->getCurrent();
    }

    protected function checkBodyNodes($body)
    {
        // check that the body does not contain non-empty output nodes
        foreach ($body as $node)
        {
            if (
                ($node instanceof ChartDown_Node_Text && !ctype_space($node->getAttribute('data')))
                ||
                (!$node instanceof ChartDown_Node_Text && !$node instanceof ChartDown_Node_BlockReference && $node instanceof ChartDown_NodeOutputInterface)
            ) {
                throw new ChartDown_Error_Syntax(sprintf('A template that extends another one cannot have a body (%s).', $node), $node->getLine());
            }
        }
    }
}
