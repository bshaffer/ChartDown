<?php

class ChartDown_Tests_LexerTest extends PHPUnit_Framework_TestCase
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

  public function testTokenizeMetadataLine()
  {
    $chart = '# title: The Good Kind';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_METADATA)
METADATA_KEY_TYPE(title)
METADATA_VALUE_TYPE(The Good Kind)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);

  }

  public function testTokenizeChordLineWithLyricLine()
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
  
  public function testTokenizeBasicChordAndExpressionLine()
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

  public function testTokenizeAdvancedChordAndExpressionLine()
  {
    $chart = '_*G#m7 | >*G7add9~ | ^Cm | !Dm7';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
EXPRESSION_TYPE(_)
EXPRESSION_TYPE(*)
CHORD_TYPE(G#m7)
BAR_LINE()
EXPRESSION_TYPE(>)
EXPRESSION_TYPE(*)
CHORD_TYPE(G7add9)
EXPRESSION_TYPE(~)
BAR_LINE()
EXPRESSION_TYPE(^)
CHORD_TYPE(Cm)
BAR_LINE()
EXPRESSION_TYPE(!)
CHORD_TYPE(Dm7)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }

  public function testTokenizeMultipleChordAndLyricLines()
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

  public function testLexTextLine()
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

  public function testTokenizeChordLineWithManyExpressions()
  {
    $chart = '>G*~ | G* | G^ | >D*';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
EXPRESSION_TYPE(>)
CHORD_TYPE(G)
EXPRESSION_TYPE(*)
EXPRESSION_TYPE(~)
BAR_LINE()
CHORD_TYPE(G)
EXPRESSION_TYPE(*)
BAR_LINE()
CHORD_TYPE(G)
EXPRESSION_TYPE(^)
BAR_LINE()
EXPRESSION_TYPE(>)
CHORD_TYPE(D)
EXPRESSION_TYPE(*)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeChordLineWithChordGroups()
  {
    $chart = 'G | [G C] [C D] | G | G';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
CHORD_TYPE(G)
BAR_LINE()
CHORD_GROUP_START_TYPE()
CHORD_TYPE(G)
CHORD_TYPE(C)
CHORD_GROUP_END_TYPE()
CHORD_GROUP_START_TYPE()
CHORD_TYPE(C)
CHORD_TYPE(D)
CHORD_GROUP_END_TYPE()
BAR_LINE()
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(G)
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
