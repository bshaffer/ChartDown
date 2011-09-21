<?php

class ChartDown_Chart_Rhythm implements ChartDown_Chart_Rhythm_FixedMeterInterface, ChartDown_Chart_Rhythm_RelativeMeterInterface
{
    public function __construct($rhythm = null)
    {
        // do nothing, we only have support for one type of rhythm at the moment
    }
    
    public function getFixedMeter()
    {
        return 1;
    }
    
    public function getRelativeMeter()
    {
        return 1;
    }
}