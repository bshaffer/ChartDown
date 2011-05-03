<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Interface implemented by token parsers.
 *
 * @package chartdown
 * @author  Fabien Potencier <fabien.potencier@symfony-project.com>
 */
interface ChartDown_TokenParserInterface
{
    /**
     * Sets the parser associated with this token parser
     *
     * @param $parser A ChartDown_Parser instance
     */
    function setParser(ChartDown_Parser $parser);

    /**
     * Parses a token and returns a node.
     *
     * @param ChartDown_Token $token A ChartDown_Token instance
     *
     * @return ChartDown_NodeInterface A ChartDown_NodeInterface instance
     */
    function parse(ChartDown_Token $token);

    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    function getTag();
}
