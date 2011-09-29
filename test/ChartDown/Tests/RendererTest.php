<?php

class ChartDown_Tests_RendererTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->env = new ChartDown_Environment();
  }

  public function testRenderHtml()
  {
    $renderer = new ChartDown_Renderer_Html();
    $chart = new ChartDown_Chart_Test($this->env);
    $html = $renderer->render($chart);

    $this->assertContains('<html' , $html);
    $this->assertContains('<body' , $html);
    $this->assertContains('You better shop around', $html);
  }
  
  public function testRenderPdf()
  {
    $renderer = new ChartDown_Renderer_Pdf();
    $chart = new ChartDown_Chart_Test($this->env);
    $filename = tempnam(sys_get_temp_dir(), 'chartdown') . '.pdf';
    $renderer->render($chart, $filename);
    
    $this->assertTrue(file_exists($filename));
    
    unlink($filename);
  }
}

class ChartDown_Chart_Test extends ChartDown\Chart\Chart
{
  public function setup()
  {
    $this
      ->addBar()
        ->addChord('G')
        ->setText('My momma told me')
      ->end()
      ->addBar()
        ->addChord('C')
        ->addChord('D')
        ->setText('You better shop around')
      ->end()
    ;
    
    $this->setTitle('Shop Around');
    $this->setArtist('The Miracles');
  }
}