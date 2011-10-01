<?php

use ChartDown\Chart\Chart;
use ChartDown\Chart\Row;

class ChartDown_Tests_Chart_ChartTest extends PHPUnit_Framework_TestCase
{
    public function testAddTopAndBottomText()
    {
        $chart = new Chart();
        $chart
            ->addRow()
                ->setType(Row::TYPE_TEXT)
                ->addBar()
                    ->setText('h1. Chorus')
                ->end()
            ->end()
            ->addRow()
                ->setType(Row::TYPE_CHORD)
                ->addBar()
                    ->addChord('C')
                ->end()
                ->addBar()
                    ->addChord('D')
                ->end()
                ->addBar()
                    ->addChord('G')
                ->end()
                ->addBar()
                    ->addChord('G')
                ->end()
            ->end()
            ->addRow()
                ->setType(Row::TYPE_TEXT)
                ->addBar()
                    ->setText('Thank God')
                ->end()
                ->addBar()
                    ->setText('I\'m a')
                ->end()
                ->addBar()
                    ->setText('country boy')
                ->end()
            ->end()
        ;
        
        $this->assertEquals(3, count($rows = $chart->getRows()));

        // first row
        $this->assertEquals($rows[0]->getType(), Row::TYPE_TEXT);
        $this->assertEquals(1, count($bars = $rows[0]->getBars()));

        // second row
        $this->assertEquals($rows[1]->getType(), Row::TYPE_CHORD);
        $this->assertEquals(4, count($bars = $rows[1]->getBars()));

        // third row
        $this->assertEquals($rows[2]->getType(), Row::TYPE_TEXT);
        $this->assertEquals(3, count($bars = $rows[2]->getBars()));
    }
    
    public function testTextile()
    {
        $chart = new Chart();
        $chart
            ->addRow()
                ->addBar()
                    ->setText('h1. Chorus')
                ->end()
            ->end()
        ;
        
        $this->assertEquals(1, count($rows = $chart->getRows()));

        // the row
        // $this->assertTrue($rows[0]->hasText());
        $this->assertEquals(1, count($bars = $rows[0]->getBars()));

        // check top text
        $this->assertEquals("\t<h1>Chorus</h1>", $bars[0]->getText()->getText());
    }
}