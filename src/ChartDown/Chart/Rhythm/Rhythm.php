<?php

namespace ChartDown\Chart\Rhythm;

class Rhythm implements FixedMeterInterface, RelativeMeterInterface
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