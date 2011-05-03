<?php

/**
* 
*/
class ChartDown_Renderer_Html implements ChartDown_RendererInterface
{
  protected $templates;
  
  public function __construct($templates = array(), $options = array())
  {
    $this->templates = array_merge(array(
      'default' => dirname(__FILE__).'/../../../data/template/chart.php',
    ), $templates);
  }
  
  public function render(ChartDown_Chart $chart, $template = 'default')
  {
    ob_start();
    include_once($this->templates[$template]);
    return ob_get_clean();
  }
}
