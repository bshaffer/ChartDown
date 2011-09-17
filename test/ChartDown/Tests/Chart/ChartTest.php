<?php

class ChartDown_Tests_Chart_ChartTest extends PHPUnit_Framework_TestCase
{
    public function testAddTopAndBottomText()
    {
        $chart = new ChartDown_Chart();
        $chart
            ->addBar()
                ->setText('h1. Chorus')
                ->addChord('C')
                ->setText('Thank God')
            ->end()
        ;

        $chart
            ->addBar()
                ->addChord('D')
                ->setText('I\'m a')
            ->end()
            ->addBar()
                ->addChord('G')
                ->setText('country boy')
            ->end()
            ->addBar()
                ->addChord('G')
            ->end()
        ;
        
        $this->assertEquals(1, count($rows = $chart->getRows()));

        // the row
        $this->assertTrue($rows[0]->hasTopText());
        $this->assertEquals(4, count($bars = $rows[0]->getBars()));

        // first bar
        $this->assertTrue($bars[0]->hasTopText());
        $this->assertTrue($bars[0]->hasBottomText());

        // second bar
        $this->assertFalse($bars[1]->hasTopText());
        $this->assertTrue($bars[1]->hasBottomText());

        // third bar
        $this->assertFalse($bars[2]->hasTopText());
        $this->assertTrue($bars[2]->hasBottomText());

        // third bar
        $this->assertFalse($bars[3]->hasTopText());
        $this->assertFalse($bars[3]->hasBottomText());
    }
    
    public function testMultilineTopText()
    {
        $chart = new ChartDown_Chart();
        $chart
            ->addBar()
                ->setText('h1. Chorus')
                ->setText('p. softer')
                ->addChord('D')
            ->end()
        ;
        
        $this->assertEquals(1, count($rows = $chart->getRows()));

        // the row
        $this->assertTrue($rows[0]->hasTopText());
        $this->assertEquals(1, count($bars = $rows[0]->getBars()));

        // check top text
        $this->assertEquals("h1. Chorus\n\np. softer", (string) $bars[0]->getTopText());
    }
    
    public function testTextile()
    {
        $chart = new ChartDown_Chart();
        $chart
            ->addBar()
                ->setText('h1. Chorus')
                ->setText('p. softer')
                ->addChord('D')
            ->end()
        ;
        
        $this->assertEquals(1, count($rows = $chart->getRows()));

        // the row
        $this->assertTrue($rows[0]->hasTopText());
        $this->assertEquals(1, count($bars = $rows[0]->getBars()));

        // check top text
        $this->assertEquals("\t<h1>Chorus</h1>\n\n\t<p>softer</p>", $bars[0]->getTopText()->getText());
    }
}