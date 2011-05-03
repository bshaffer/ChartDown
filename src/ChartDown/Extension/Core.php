<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ChartDown_Extension_Core extends ChartDown_Extension
{
    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of ChartDown_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(
            new ChartDown_TokenParser_Chord(),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'core';
    }
}
