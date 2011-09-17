<?php

class ChartDown_Tests_Lexer_TextTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->lexer = new ChartDown_Lexer($chartdown);
  }

  public function testTokenizeTextLine()
  {
    $chart = <<<EOF
text:Don't miss that train|comin' home|Don't miss that train|to me
EOF;

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_TEXT)
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(comin' home)
BAR_LINE()
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(to me)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeTopTextLine()
  {
    $chart = <<<EOF
text: h1. Chorus
G | G | C | D
EOF;

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_TEXT)
TEXT_TYPE(h1. Chorus)
LINE_END()
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

  public function testTokenizeBottomTextLine()
  {
    $chart = <<<EOF
G | G | C | D
text: Don't miss that train|comin' home|Don't miss that train|to me
EOF;

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
LINE_START(STATE_TEXT)
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(comin' home)
BAR_LINE()
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(to me)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeTopAndBottomTextLines()
  {
    $chart = <<<EOF
text: h1. Chorus | | | p>. _D.S. Al Coda_
G | G | C | D
text: Don't miss that train|comin' home|Don't miss that train|to me
EOF;

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_TEXT)
TEXT_TYPE(h1. Chorus)
BAR_LINE()
BAR_LINE()
BAR_LINE()
TEXT_TYPE(p>. _D.S. Al Coda_)
LINE_END()
LINE_START(STATE_CHORD)
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
CHORD_TYPE(D)
LINE_END()
LINE_START(STATE_TEXT)
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(comin' home)
BAR_LINE()
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(to me)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeMultipleChordAndTextLines()
  {
    $chart = <<<EOF
G | G | C | D
text: Don't miss that train|comin' home|Don't miss that train|to me
--
G | G | C | D
text: We're on a plane|blowin' smoke|signin' our names|en chelo
EOF;

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
LINE_START(STATE_TEXT)
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(comin' home)
BAR_LINE()
TEXT_TYPE(Don't miss that train)
BAR_LINE()
TEXT_TYPE(to me)
LINE_END()
END_ROW_TYPE()
LINE_START(STATE_CHORD)
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
CHORD_TYPE(D)
LINE_END()
LINE_START(STATE_TEXT)
TEXT_TYPE(We're on a plane)
BAR_LINE()
TEXT_TYPE(blowin' smoke)
BAR_LINE()
TEXT_TYPE(signin' our names)
BAR_LINE()
TEXT_TYPE(en chelo)
LINE_END()
EOF_TYPE()
EOF;

    $this->assertEquals($expectedOutput, (string) $output);
  }
}
