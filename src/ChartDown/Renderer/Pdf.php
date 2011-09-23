<?php

/**
* 
*/
class ChartDown_Renderer_Pdf implements ChartDown_RendererInterface
{
  protected $pdf;
  protected $renderer;
  
  public function __construct($pdf = null, $renderer = null)
  {
    if (is_null($pdf)) {
      $pdf = new Knplabs\Snappy\Pdf('wkhtmltopdf');
    }
    
    if (is_null($renderer)) {
      $renderer = new ChartDown_Renderer_Html();
    }

    $this->pdf = $pdf;
    $this->renderer = $renderer;
  }
  
  public function render($chart, $path = null, $template = 'default')
  {
    if ($chart instanceof ChartDown_Chart) {
      $chart = $this->renderer->render($chart, null, $template);
    }

    return $path ? $this->pdf->save($chart, $path) : $this->pdf->get($chart);
  }
}
