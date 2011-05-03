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
 * Interface implemented by extension classes.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
interface ChartDown_ExtensionInterface
{
    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param ChartDown_Environment $environment The current ChartDown_Environment instance
     */
    function initRuntime(ChartDown_Environment $environment);

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of ChartDown_TokenParserInterface or ChartDown_TokenParserBrokerInterface instances
     */
    function getTokenParsers();

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of ChartDown_NodeVisitorInterface instances
     */
    function getNodeVisitors();

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    function getGlobals();

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    function getOperators();

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    function getName();
}
