<?php

class ChartDown_Tests_Lexer_RhythmTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->lexer = new ChartDown_Lexer($chartdown);
  }

  public function testTokenizeChordLineWithChordGroups()
  {
    $chart = '. G | [G C] . [C D] . | . . . G | G';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
LINE_START(STATE_CHORD)
RHYTHM_TYPE(.)
CHORD_TYPE(G)
BAR_LINE()
CHORD_GROUP_START_TYPE()
CHORD_TYPE(G)
CHORD_TYPE(C)
CHORD_GROUP_END_TYPE()
RHYTHM_TYPE(.)
CHORD_GROUP_START_TYPE()
CHORD_TYPE(C)
CHORD_TYPE(D)
CHORD_GROUP_END_TYPE()
RHYTHM_TYPE(.)
BAR_LINE()
RHYTHM_TYPE(.)
RHYTHM_TYPE(.)
RHYTHM_TYPE(.)
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(G)
LINE_END()
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
}
