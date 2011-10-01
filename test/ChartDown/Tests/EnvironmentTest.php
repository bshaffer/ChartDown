<?php

class ChartDown_Tests_EnvironmentTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->chartdown = new ChartDown_Environment();
  }
  
  public function testRenderEmptyString()
  {
    $chart = $this->chartdown->loadChart("text: Here Comes The Sun", 'Here Comes The Sun');
    $this->assertEquals("text: Here Comes The Sun", $chart->getChartName());
  }
}
