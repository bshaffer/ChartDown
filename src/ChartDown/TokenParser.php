<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for all token parsers.
 *
 * @package chartdown
 * @author  Fabien Potencier <fabien.potencier@symfony-project.com>
 */
abstract class ChartDown_TokenParser implements ChartDown_TokenParserInterface
{
    protected $parser;

    /**
     * Sets the parser associated with this token parser
     *
     * @param $parser A ChartDown_Parser instance
     */
    public function setParser(ChartDown_Parser $parser)
    {
        $this->parser = $parser;
    }
}
