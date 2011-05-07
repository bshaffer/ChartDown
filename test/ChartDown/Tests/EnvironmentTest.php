<?php

class ChartDown_Tests_EnvironmentTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->chartdown = new ChartDown_Environment();
  }
  
  public function testRenderEmptyString()
  {
    $chart = $this->chartdown->loadChart("\nHere Comes The Sun");
    $this->assertEquals("\nHere Comes The Sun", $chart->getChartName());
  }
}
