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
      $pdf = new Knplabs\Snappy\Pdf('env wkhtmltopdf');
    }
    
    if (is_null($renderer)) {
      $renderer = new ChartDown_Renderer_Html();
    }

    $this->pdf = $pdf;
    $this->renderer = $renderer;
  }
  
  public function render(ChartDown_Chart $chart, $path = null)
  {
    $html = $this->renderer->render($chart);
    
    return $path ? $this->pdf->save($html, $path) : $this->pdf->get($html);
  }
}
