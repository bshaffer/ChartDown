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
 * Stores the ChartDown configuration.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Environment
{
    const VERSION = '0.0.1';

    protected $loader;
    protected $debug;
    protected $lexer;
    protected $parser;
    protected $compiler;
    protected $extensions;
    protected $parsers;
    protected $visitors;
    protected $globals;
    protected $unaryOperators;
    protected $binaryOperators;
    protected $baseChartClass;
    protected $runtimeInitialized;
    protected $expressionTypes;
    protected $chartClassPrefix = '__ChartDownChart_';

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * debug: When set to `true`, this will do something awesome
     *
     * @param array                  $options An array of options
     */
    public function __construct($options = array())
    {
        $options = array_merge(array(
            'debug'               => false,
            'base_chart_class'    => 'ChartDown\Chart\Chart',
        ), $options);

        $this->extensions         = array(
            'core'      => new ChartDown_Extension_Core(),
        );
        
        $this->baseChartClass  = $options['base_chart_class'];
        
        $this->debug              = (bool) $options['debug'];
        $this->loader             = new ChartDown_Loader_String();
        
        $this->expressionTypes = array(
            'Accent'           => new \ChartDown\Chart\ExpressionType\Accent(),
            'Anticipation'     => new \ChartDown\Chart\ExpressionType\Anticipation(),
            'Coda'             => new \ChartDown\Chart\ExpressionType\Coda(),
            'Diamond'          => new \ChartDown\Chart\ExpressionType\Diamond(),
            'Fermata'          => new \ChartDown\Chart\ExpressionType\Fermata(),
            'RepeatBar'        => new \ChartDown\Chart\ExpressionType\RepeatBar(),
            'RepeatEnding'     => new \ChartDown\Chart\ExpressionType\RepeatEnding(),
            'RepeatFinish'     => new \ChartDown\Chart\ExpressionType\RepeatFinish(),
            'RepeatStart'      => new \ChartDown\Chart\ExpressionType\RepeatStart(),
            'Segno'            => new \ChartDown\Chart\ExpressionType\Segno(),
            'Tenudo'           => new \ChartDown\Chart\ExpressionType\Tenudo(),
            'Tie'              => new \ChartDown\Chart\ExpressionType\Tie(),
        );
    }

    /**
     * Gets the base template class for compiled templates.
     *
     * @return string The base template class name
     */
    public function getBaseChartClass()
    {
        return $this->baseChartClass;
    }
    
    /**
     * Gets the array of expression types used in the chart
     *
     * @return array The ExpressionTypeInterface Objects
     */
    public function getExpressionTypes()
    {
        return $this->expressionTypes;
    }

    /**
     * Sets the base template class for compiled templates.
     *
     * @param string $class The base template class name
     */
    public function setBaseChartClass($class)
    {
        $this->baseChartClass = $class;
    }

    /**
     * Enables debugging mode.
     */
    public function enableDebug()
    {
        $this->debug = true;
    }

    /**
     * Disables debugging mode.
     */
    public function disableDebug()
    {
        $this->debug = false;
    }

    /**
     * Checks if debug mode is enabled.
     *
     * @return Boolean true if debug mode is enabled, false otherwise
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Gets the template class associated with the given string.
     *
     * @param string $name The name for which to calculate the template class name
     *
     * @return string The template class name
     */
    public function getChartClass($name)
    {
        return $this->chartClassPrefix.md5($this->loader->getCacheKey($name));
    }

    public function getCacheFilename($name)
    {
      // Not implemented yet!
      return false;
    }

    /**
     * Loads a template by name.
     *
     * @param  string  $name  The template name
     *
     * @return ChartDown\Chart\Chart A template instance representing the given template name
     */
    public function loadChart($name)
    {
        $cls = $this->getChartClass($name);

        if (isset($this->loadedCharts[$cls])) {
            return $this->loadedCharts[$cls];
        }

        if (!class_exists($cls, false)) {
            if (false === $cache = $this->getCacheFilename($name)) {
                eval('?>'.$this->compileSource($this->loader->getSource($name), $name));
            } else {
                if (!file_exists($cache) || ($this->isAutoReload() && !$this->loader->isFresh($name, filemtime($cache)))) {
                    $this->writeCacheFile($cache, $this->compileSource($this->loader->getSource($name), $name));
                }

                require_once $cache;
            }
        }

        if (!$this->runtimeInitialized) {
            $this->initRuntime();
        }

        return $this->loadedCharts[$cls] = new $cls($this);
    }

    /**
     * Gets the Lexer instance.
     *
     * @return ChartDown_LexerInterface A ChartDown_LexerInterface instance
     */
    public function getLexer()
    {
        if (null === $this->lexer) {
            $this->lexer = new ChartDown_Lexer($this);
        }

        return $this->lexer;
    }

    /**
     * Sets the Lexer instance.
     *
     * @param ChartDown_LexerInterface A ChartDown_LexerInterface instance
     */
    public function setLexer(ChartDown_LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Tokenizes a source code.
     *
     * @param string $source The template source code
     * @param string $name   The template name
     *
     * @return ChartDown_TokenStream A ChartDown_TokenStream instance
     */
    public function tokenize($source, $name = null)
    {
        return $this->getLexer()->tokenize($source, $name);
    }

    /**
     * Gets the Parser instance.
     *
     * @return ChartDown_ParserInterface A ChartDown_ParserInterface instance
     */
    public function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new ChartDown_Parser($this);
        }

        return $this->parser;
    }

    /**
     * Sets the Parser instance.
     *
     * @param ChartDown_ParserInterface A ChartDown_ParserInterface instance
     */
    public function setParser(ChartDown_ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parses a token stream.
     *
     * @param ChartDown_TokenStream $tokens A ChartDown_TokenStream instance
     *
     * @return ChartDown_Node_Module A Node tree
     */
    public function parse(ChartDown_TokenStream $tokens)
    {
        return $this->getParser()->parse($tokens);
    }

    /**
     * Gets the Compiler instance.
     *
     * @return ChartDown_CompilerInterface A ChartDown_CompilerInterface instance
     */
    public function getCompiler()
    {
        if (null === $this->compiler) {
            $this->compiler = new ChartDown_Compiler($this);
        }

        return $this->compiler;
    }

    /**
     * Sets the Compiler instance.
     *
     * @param ChartDown_CompilerInterface $compiler A ChartDown_CompilerInterface instance
     */
    public function setCompiler(ChartDown_CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Registers a Node Visitor.
     *
     * @param ChartDown_NodeVisitorInterface $visitor A ChartDown_NodeVisitorInterface instance
     */
    public function addNodeVisitor(ChartDown_NodeVisitorInterface $visitor)
    {
        if (null === $this->visitors) {
            $this->getNodeVisitors();
        }

        $this->visitors[] = $visitor;
    }

    /**
     * Gets the registered Node Visitors.
     *
     * @return ChartDown_NodeVisitorInterface[] An array of ChartDown_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        if (null === $this->visitors) {
            $this->visitors = array();
            foreach ($this->getExtensions() as $extension) {
                $this->visitors = array_merge($this->visitors, $extension->getNodeVisitors());
            }
        }

        return $this->visitors;
    }

    /**
     * Compiles a Node.
     *
     * @param ChartDown_NodeInterface $node A ChartDown_NodeInterface instance
     *
     * @return string The compiled PHP source code
     */
    public function compile(ChartDown_NodeInterface $node)
    {
        return $this->getCompiler()->compile($node)->getSource();
    }

    /**
     * Compiles a template source code.
     *
     * @param string $source The template source code
     * @param string $name   The template name
     *
     * @return string The compiled PHP source code
     */
    public function compileSource($source, $name = null)
    {
        return $this->compile($this->parse($this->tokenize($source, $name)));
    }
    
    public function initRuntime()
    {
        $this->runtimeInitialized = true;

        foreach ($this->getExtensions() as $extension) {
            $extension->initRuntime($this);
        }
    }

    /**
     * Returns true if the given extension is registered.
     *
     * @param string $name The extension name
     *
     * @return Boolean Whether the extension is registered or not
     */
    public function hasExtension($name)
    {
        return isset($this->extensions[$name]);
    }

    /**
     * Gets an extension by name.
     *
     * @param string $name The extension name
     *
     * @return ChartDown_ExtensionInterface A ChartDown_ExtensionInterface instance
     */
    public function getExtension($name)
    {
        if (!isset($this->extensions[$name])) {
            throw new ChartDown_Error_Runtime(sprintf('The "%s" extension is not enabled.', $name));
        }

        return $this->extensions[$name];
    }

    /**
     * Registers an extension.
     *
     * @param ChartDown_ExtensionInterface $extension A ChartDown_ExtensionInterface instance
     */
    public function addExtension(ChartDown_ExtensionInterface $extension)
    {
        $this->extensions[$extension->getName()] = $extension;
    }

    /**
     * Removes an extension by name.
     *
     * @param string $name The extension name
     */
    public function removeExtension($name)
    {
        unset($this->extensions[$name]);
    }

    /**
     * Registers an array of extensions.
     *
     * @param array $extensions An array of extensions
     */
    public function setExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }

    /**
     * Returns all registered extensions.
     *
     * @return array An array of extensions
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Registers a Token Parser.
     *
     * @param ChartDown_TokenParserInterface $parser A ChartDown_TokenParserInterface instance
     */
    public function addTokenParser(ChartDown_TokenParserInterface $parser)
    {
        if (null === $this->parsers) {
            $this->getTokenParsers();
        }

        $this->parsers->addTokenParser($parser);
    }

    /**
     * Gets the registered Token Parsers.
     *
     * @return ChartDown_TokenParserInterface[] An array of ChartDown_TokenParserInterface instances
     */
    public function getTokenParsers()
    {
        if (null === $this->parsers) {
            $this->parsers = new ChartDown_TokenParserBroker;
            foreach ($this->getExtensions() as $extension) {
                $parsers = $extension->getTokenParsers();
                foreach($parsers as $parser) {
                    if ($parser instanceof ChartDown_TokenParserInterface) {
                        $this->parsers->addTokenParser($parser);
                    } else if ($parser instanceof ChartDown_TokenParserBrokerInterface) {
                        $this->parsers->addTokenParserBroker($parser);
                    } else {
                        throw new ChartDown_Error_Runtime('getTokenParsers() must return an array of ChartDown_TokenParserInterface or ChartDown_TokenParserBrokerInterface instances');
                    }
                }
            }
        }

        return $this->parsers;
    }

    /**
     * Registers a Global.
     *
     * @param string $name  The global name
     * @param mixed  $value The global value
     */
    public function addGlobal($name, $value)
    {
        if (null === $this->globals) {
            $this->getGlobals();
        }

        $this->globals[$name] = $value;
    }

    /**
     * Gets the registered Globals.
     *
     * @return array An array of globals
     */
    public function getGlobals()
    {
        if (null === $this->globals) {
            $this->globals = array();
            foreach ($this->getExtensions() as $extension) {
                $this->globals = array_merge($this->globals, $extension->getGlobals());
            }
        }

        return $this->globals;
    }

    /**
     * Gets the registered unary Operators.
     *
     * @return array An array of unary operators
     */
    public function getUnaryOperators()
    {
        if (null === $this->unaryOperators) {
            $this->initOperators();
        }

        return $this->unaryOperators;
    }

    /**
     * Gets the registered binary Operators.
     *
     * @return array An array of binary operators
     */
    public function getBinaryOperators()
    {
        if (null === $this->binaryOperators) {
            $this->initOperators();
        }

        return $this->binaryOperators;
    }
    
    /**
     * Do we need these checks?
     *
     * @author Brent Shaffer
     */
    public function chartHasSetter($setter)
    {
        switch(trim($setter))
        {
          case 'title':
          case 'artist':
          case 'time':
          case 'time_signature':
          case 'time signature':
          case 'key':
          case 'key_signature':
          case 'key signature':
          case 'tempo':
            return true;
        }
        
        return false;
    }

    protected function initOperators()
    {
        $this->unaryOperators = array();
        $this->binaryOperators = array();
        foreach ($this->getExtensions() as $extension) {
            $operators = $extension->getOperators();

            if (!$operators) {
                continue;
            }

            if (2 !== count($operators)) {
                throw new InvalidArgumentException(sprintf('"%s::getOperators()" does not return a valid operators array.', get_class($extension)));
            }

            $this->unaryOperators = array_merge($this->unaryOperators, $operators[0]);
            $this->binaryOperators = array_merge($this->binaryOperators, $operators[1]);
        }
    }
}
