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
 * Interface implemented by parser classes.
 *
 * @package chartdown
 * @author  Fabien Potencier <fabien.potencier@symfony-project.com>
 */
interface ChartDown_ParserInterface
{
    /**
     * Converts a token stream to a node tree.
     *
     * @param  ChartDown_TokenStream $stream A token stream instance
     *
     * @return ChartDown_Node_Module A node tree
     */
    function parse(ChartDown_TokenStream $code);
}
