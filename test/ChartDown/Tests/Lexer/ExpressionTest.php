<?php

class ChartDown_Tests_Lexer_ExpressionTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->lexer = new ChartDown_Lexer($chartdown);
  }
  
  public function testTokenizeChordLineWithAnticipations()
  {
    $chart = 'G | ^C | G | ^D';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
CHORD_TYPE(G)
BAR_LINE()
EXPRESSION_TYPE(^)
CHORD_TYPE(C)
BAR_LINE()
CHORD_TYPE(G)
BAR_LINE()
EXPRESSION_TYPE(^)
CHORD_TYPE(D)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeChordLineWithTies()
  {
    $chart = 'G~ | C | G ~ | D';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
CHORD_TYPE(G)
EXPRESSION_TYPE(~)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
CHORD_TYPE(G)
EXPRESSION_TYPE(~)
BAR_LINE()
CHORD_TYPE(D)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeChordLineWithRepeats()
  {
    $chart = '{: G | C | {1} G | C | {2} G | D :}';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
EXPRESSION_TYPE({:)
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
EXPRESSION_TYPE({1})
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
EXPRESSION_TYPE({2})
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(D)
EXPRESSION_TYPE(:})
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeBasicExpressionLine()
  {
    $chart = '*G | >G | ^C | !D';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
EXPRESSION_TYPE(*)
CHORD_TYPE(G)
BAR_LINE()
EXPRESSION_TYPE(>)
CHORD_TYPE(G)
BAR_LINE()
EXPRESSION_TYPE(^)
CHORD_TYPE(C)
BAR_LINE()
EXPRESSION_TYPE(!)
CHORD_TYPE(D)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeAdvancedExpressionLine()
  {
    $chart = '{: _*G#m7 | >*G7add9~ |{1} ^Cm | !Dm7 $ :}';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
EXPRESSION_TYPE({:)
EXPRESSION_TYPE(_)
EXPRESSION_TYPE(*)
CHORD_TYPE(G#m7)
BAR_LINE()
EXPRESSION_TYPE(>)
EXPRESSION_TYPE(*)
CHORD_TYPE(G7add9)
EXPRESSION_TYPE(~)
BAR_LINE()
EXPRESSION_TYPE({1})
EXPRESSION_TYPE(^)
CHORD_TYPE(Cm)
BAR_LINE()
EXPRESSION_TYPE(!)
CHORD_TYPE(Dm7)
EXPRESSION_TYPE($)
EXPRESSION_TYPE(:})
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
}
