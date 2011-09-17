<?php

class ChartDown_Tests_Chart_ExpressionTest extends PHPUnit_Framework_TestCase
{
    public function testBasicExpressions()
    {
        $this->assertMatches('^', new ChartDown_Chart_ExpressionType_Accent());
        $this->assertMatches('>', new ChartDown_Chart_ExpressionType_Anticipation());
        $this->assertMatches('*', new ChartDown_Chart_ExpressionType_Diamond());
        $this->assertMatches('!', new ChartDown_Chart_ExpressionType_Fermata());
        $this->assertMatches('_', new ChartDown_Chart_ExpressionType_Tenudo());

        $this->assertMatches('&', new ChartDown_Chart_ExpressionType_Segno());
        $this->assertMatches('$', new ChartDown_Chart_ExpressionType_Coda());
        $this->assertMatches('~', new ChartDown_Chart_ExpressionType_Tie());
    }

    public function testParseRepeatExpressions()
    {
        $this->assertMatches('%', new ChartDown_Chart_ExpressionType_RepeatBar());
        $this->assertMatches('{1}', new ChartDown_Chart_ExpressionType_RepeatEnding());
        $this->assertMatches('{2}', new ChartDown_Chart_ExpressionType_RepeatEnding());
        $this->assertMatches('{:', new ChartDown_Chart_ExpressionType_RepeatStart());
        $this->assertMatches(':}', new ChartDown_Chart_ExpressionType_RepeatFinish());

        $this->assertNoMatch('{#}', new ChartDown_Chart_ExpressionType_RepeatEnding());
    }

    public function testCombiningMultipleValidExpressions()
    {
        $this->assertGroupMatches('^*', array(
            new ChartDown_Chart_ExpressionType_Accent(),
            new ChartDown_Chart_ExpressionType_Diamond(),
        ));

        $this->assertGroupMatches('_^>!*', array(
            new ChartDown_Chart_ExpressionType_Accent(),
            new ChartDown_Chart_ExpressionType_Anticipation(),
            new ChartDown_Chart_ExpressionType_Diamond(),
            new ChartDown_Chart_ExpressionType_Fermata(),
            new ChartDown_Chart_ExpressionType_Tenudo(),
        ));
    }

    private function assertMatches($symbol, ChartDown_Chart_ExpressionTypeInterface $type)
    {
        $this->assertEquals(1, preg_match(sprintf('/%s/', $type->getRegex()), $symbol));
    }

    private function assertNoMatch($symbol, ChartDown_Chart_ExpressionTypeInterface $type)
    {
        $this->assertEquals(0, preg_match(sprintf('/%s/', $type->getRegex()), $symbol));
    }

    private function assertGroupMatches($symbols, array $types)
    {
        $regex = array();
        foreach ($types as $type) {
            $regex[] = $type->getRegex();
        }

        $this->assertEquals(1, preg_match(sprintf('/%s/', implode('|', $regex)), $symbols));
    }
}
