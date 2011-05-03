<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2010 Fabien Potencier
 * (c) 2010 Arnaud Le Blanc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Default implementation of a token parser broker.
 *
 * @package chartdown
 * @author  Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
class ChartDown_TokenParserBroker implements ChartDown_TokenParserBrokerInterface
{
    protected $parser;
    protected $parsers = array();
    protected $brokers = array();

    /**
     * Constructor.
     *
     * @param array|Traversable $parsers A Traversable of ChartDown_TokenParserInterface instances
     * @param array|Traversable $brokers A Traversable of ChartDown_TokenParserBrokerInterface instances
     */
    public function __construct($parsers = array(), $brokers = array())
    {
        foreach($parsers as $parser) {
            if (!$parser instanceof ChartDown_TokenParserInterface) {
                throw new ChartDown_Error('$parsers must a an array of ChartDown_TokenParserInterface');
            }
            $this->parsers[$parser->getTag()] = $parser;
        }
        foreach($brokers as $broker) {
            if (!$broker instanceof ChartDown_TokenParserBrokerInterface) {
                throw new ChartDown_Error('$brokers must a an array of ChartDown_TokenParserBrokerInterface');
            }
            $this->brokers[] = $broker;
        }
	}

    /**
     * Adds a TokenParser.
     *
     * @param ChartDown_TokenParserInterface $parser A ChartDown_TokenParserInterface instance
     */
    public function addTokenParser(ChartDown_TokenParserInterface $parser)
    {
        $this->parsers[$parser->getTag()] = $parser;
    }

    /**
     * Adds a TokenParserBroker.
     *
     * @param ChartDown_TokenParserBroker $broker A ChartDown_TokenParserBroker instance
     */
    public function addTokenParserBroker(ChartDown_TokenParserBroker $broker)
    {
        $this->brokers[] = $broker;
    }

    /**
     * Gets a suitable TokenParser for a tag.
     *
     * First looks in parsers, then in brokers.
     *
     * @param string $tag A tag name
     *
     * @return null|ChartDown_TokenParserInterface A ChartDown_TokenParserInterface or null if no suitable TokenParser was found
     */
    public function getTokenParser($tag)
    {
        if (isset($this->parsers[$tag])) {
            return $this->parsers[$tag];
        }
        $broker = end($this->brokers);
        while (false !== $broker) {
            $parser = $broker->getTokenParser($tag);
            if (null !== $parser) {
                return $parser;
            }
            $broker = prev($this->brokers);
        }
        return null;
    }

    public function getParser()
    {
        return $this->parser;
    }

    public function setParser(ChartDown_ParserInterface $parser)
    {
        $this->parser = $parser;
        foreach ($this->parsers as $tokenParser) {
            $tokenParser->setParser($parser);
        }
        foreach ($this->brokers as $broker) {
            $broker->setParser($parser);
        }
    }
}
