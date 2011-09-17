<?php

class ChartDown_Tests_LexerChordTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->lexer = new ChartDown_Lexer($chartdown);
  }
 
  public function testTokenizeChordLine()
  {
    $chart = 'G | G | C | D';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
CHORD_TYPE(D)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  /**
  * @expectedException ChartDown_Error_Syntax
  */
  public function testInvalidChordTokensThrowException()
  {
    $chart = <<<EOF
Gm7 | Not | A | Bm
EOF;

    $output = $this->lexer->tokenize($chart);
  }
}
