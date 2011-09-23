<?php

/**
* Repeat Finish Expression
*/
class ChartDown_Chart_ExpressionType_RepeatFinish implements ChartDown_Chart_ExpressionTypeInterface, ChartDown_Chart_Rhythm_RelativeMeterInterface
{
    public function getSymbol()
    {
        return ':}';
    }
    
    public function getPosition()
    {
        return 'bar';
    }
    
    public function getName()
    {
        return 'repeat finish';
    }
    
    public function getRegex()
    {
        return ':\}';
    }
    
    public function getRelativeMeter()
    {
        return 1;
    }
}