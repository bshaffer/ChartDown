<?php

/*
 * This file is part of the symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfTemplateLoaderInterface is the interface all loaders must implement.
 *
 * @package    symfony
 * @subpackage templating
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: //depot/genesis_web/branches/falkland/bin/lib/vendor/symfony/lib/templating/sfTemplateLoaderInterface.php#2 $
 */
interface sfTemplateLoaderInterface
{
  /**
   * Loads a template.
   *
   * @param string $template The logical template name
   * @param string $renderer The renderer to use
   *
   * @return sfTemplateStorage|Boolean false if the template cannot be loaded, a sfTemplateStorage instance otherwise
   */
  function load($template, $renderer = 'php');
}
