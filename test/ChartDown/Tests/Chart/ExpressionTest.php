<?php

use ChartDown\Chart\ExpressionType;
use ChartDown\Chart\ExpressionType\ExpressionTypeInterface;

class ChartDown_Tests_Chart_ExpressionTest extends PHPUnit_Framework_TestCase
{
    public function testBasicExpressions()
    {
        $this->assertMatches('^', new ExpressionType\Accent());
        $this->assertMatches('>', new ExpressionType\Anticipation());
        $this->assertMatches('*', new ExpressionType\Diamond());
        $this->assertMatches('!', new ExpressionType\Fermata());
        $this->assertMatches('_', new ExpressionType\Tenudo());

        $this->assertMatches('&', new ExpressionType\Segno());
        $this->assertMatches('$', new ExpressionType\Coda());
        $this->assertMatches('~', new ExpressionType\Tie());
    }

    public function testParseRepeatExpressions()
    {
        $this->assertMatches('%', new ExpressionType\RepeatBar());
        $this->assertMatches('{1}', new ExpressionType\RepeatEnding());
        $this->assertMatches('{2}', new ExpressionType\RepeatEnding());
        $this->assertMatches('{:', new ExpressionType\RepeatStart());
        $this->assertMatches(':}', new ExpressionType\RepeatFinish());

        $this->assertNoMatch('{#}', new ExpressionType\RepeatEnding());
    }

    public function testCombiningMultipleValidExpressions()
    {
        $this->assertGroupMatches('^*', array(
            new ExpressionType\Accent(),
            new ExpressionType\Diamond(),
        ));

        $this->assertGroupMatches('_^>!*', array(
            new ExpressionType\Accent(),
            new ExpressionType\Anticipation(),
            new ExpressionType\Diamond(),
            new ExpressionType\Fermata(),
            new ExpressionType\Tenudo(),
        ));
    }

    private function assertMatches($symbol, ExpressionTypeInterface $type)
    {
        $this->assertEquals(1, preg_match(sprintf('/%s/', $type->getRegex()), $symbol));
    }

    private function assertNoMatch($symbol, ExpressionTypeInterface $type)
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
