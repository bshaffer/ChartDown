<?php

class ChartDown_Tests_Chart_ExpressionTest extends PHPUnit_Framework_TestCase
{
  public function testParseBasicExpressions()
  {
    $exp1 = new ChartDown_Chart_Expression('^');
    $exp2 = new ChartDown_Chart_Expression('>');
    $exp3 = new ChartDown_Chart_Expression('*');
    $exp4 = new ChartDown_Chart_Expression('.');
    $exp5 = new ChartDown_Chart_Expression('_');
    $exp6 = new ChartDown_Chart_Expression('~');
    
    $this->assertEquals($exp1->getType(), ChartDown_Chart_Expression::ACCENT);
    $this->assertEquals($exp2->getType(), ChartDown_Chart_Expression::ANTICIPATION);
    $this->assertEquals($exp3->getType(), ChartDown_Chart_Expression::DIAMOND);
    $this->assertEquals($exp4->getType(), ChartDown_Chart_Expression::STACCATO);
    $this->assertEquals($exp5->getType(), ChartDown_Chart_Expression::TENUDO);
    $this->assertEquals($exp6->getType(), ChartDown_Chart_Expression::TIE);
  }
  
  public function testParseRepeatExpressions()
  {
    $start  = new ChartDown_Chart_Expression('{:');
    $finish = new ChartDown_Chart_Expression(':}');
    $end1   = new ChartDown_Chart_Expression('{1}');
    $end5   = new ChartDown_Chart_Expression('{5}');
    
    $this->assertEquals($start->getType(), ChartDown_Chart_Expression::REPEAT_START);
    $this->assertEquals($finish->getType(), ChartDown_Chart_Expression::REPEAT_FINISH);
    $this->assertEquals($end1->getType(), ChartDown_Chart_Expression::REPEAT_ENDING);
    $this->assertEquals($end1->getValue(), '1');
    $this->assertEquals($end5->getType(), ChartDown_Chart_Expression::REPEAT_ENDING);
    $this->assertEquals($end5->getValue(), '5');
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidExpression()
  {
    $note = new ChartDown_Chart_Note('#');
  }

  /**
  * @expectedException InvalidArgumentException
  */
  public function testCombiningTwoValidExpressionsIsInvalid()
  {
    $note = new ChartDown_Chart_Note('*^');
  }
  
  /**
  * @expectedException InvalidArgumentException
  */
  public function testParseInvalidRepeat()
  {
    $note = new ChartDown_Chart_Note('{1');
  }
}
