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
 * Autoloads ChartDown classes.
 *
 * @package chartdown
 * @author  Brent Shaffer <bshafs@gmail.com>
 */
class ChartDown_Autoloader
{
    /**
     * Registers ChartDown_Autoloader as an SPL autoloader.
     */
    static public function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
        
        include_once(dirname(__FILE__).'/../../vendor/sensio/templating/sfTemplateAutoloader.php');
        sfTemplateAutoloader::register();
    }

    /**
     * Handles autoloading of classes.
     *
     * @param  string  $class  A class name.
     *
     * @return boolean Returns true if the class has been loaded
     */
    static public function autoload($class)
    {
        if (0 === strpos($class, 'Knplabs\\Snappy') && file_exists($file = dirname(__FILE__).'/../../vendor/snappy/src/'.str_replace('\\', '/', $class).'.php')) {
            require $file;
        } else if ($class == 'Textile' && file_exists($file = dirname(__FILE__).'/../../vendor/textile/Textile.php')) {
            require $file;
        } else if (0 !== strpos($class, 'ChartDown')) {
            return;
        } else if (file_exists($file = dirname(__FILE__).'/../'.str_replace('_', DIRECTORY_SEPARATOR, $class).'.php')) {
            require $file;
        } else if (file_exists($file = dirname(__FILE__).'/../'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php')) {
            require $file;
        }
    }
}
