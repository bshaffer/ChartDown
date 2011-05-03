<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class ChartDown_Extension implements ChartDown_ExtensionInterface
{
    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param ChartDown_Environment $environment The current ChartDown_Environment instance
     */
    public function initRuntime(ChartDown_Environment $environment)
    {
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of ChartDown_TokenParserInterface or ChartDown_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array();
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of ChartDown_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return array();
    }
    
    public function getGlobals()
    {
      return array();
    }
    
    public function getOperators()
    {
      return array();
    }
}
