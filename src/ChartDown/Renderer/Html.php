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
      'table' => dirname(__FILE__).'/../../../data/template/table.php',
    ), $templates);
  }

  public function render(ChartDown_Chart $chart, $template = 'default')
  {
    ob_start();
    include_once($this->templates[$template]);
    return ob_get_clean();
  }

  // Methods called in the template
  public function renderChordExpressions(ChartDown_Chart_Chord $chord)
  {
      $classes = array();
      foreach ($chord->getExpressions() as $expression) {
          $classes[] = str_replace(' ', '-', $expression->getName());
      }
      return count($classes) ? ' ' . implode(' ', $classes) : '';
  }

  public function renderBarExpressions(ChartDown_Chart_Bar $bar)
  {
      $classes = array();
      foreach ($bar->getExpressions() as $expression) {
          $classes[] = str_replace(' ', '-', $expression->getName());
      }

      return count($classes) ? ' ' . implode(' ', $classes) : '';
  }
}
