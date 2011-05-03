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
 * Interface implemented by token parser brokers.
 *
 * Token parser brokers allows to implement custom logic in the process of resolving a token parser for a given tag name.
 *
 * @package chartdown
 * @author  Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
interface ChartDown_TokenParserBrokerInterface
{
    /**
     * Gets a TokenParser suitable for a tag.
     *
     * @param  string $tag A tag name
     *
     * @return null|ChartDown_TokenParserInterface A ChartDown_TokenParserInterface or null if no suitable TokenParser was found
     */
    function getTokenParser($tag);

    /**
     * Calls ChartDown_TokenParserInterface::setParser on all parsers the implementation knows of.
     *
     * @param ChartDown_ParserInterface $parser A ChartDown_ParserInterface interface
     */
    function setParser(ChartDown_ParserInterface $parser);

    /**
     * Gets the ChartDown_ParserInterface.
     *
     * @return null|ChartDown_ParserInterface A ChartDown_ParserInterface instance of null
     */
    function getParser();
}
