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
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(G)
BAR_LINE()
CHORD_TYPE(C)
BAR_LINE()
CHORD_TYPE(D)
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
  
  public function testTokenizeMetadataLine()
  {
    $chart = '# title: The Good Kind';

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
METADATA_KEY_TYPE(title)
METADATA_VALUE_TYPE(The Good Kind)
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);

  }

  public function testTokenizeChordLineWithLyricLine()
  {
    $chart = <<<EOF
G | G | C | D
Don't miss that train | comin' home | Don't miss that train | to me
EOF;

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
CHORD_TYPE(G)
LYRIC_TYPE(Don't miss that train)
BAR_LINE()
CHORD_TYPE(G)
LYRIC_TYPE(comin' home)
BAR_LINE()
CHORD_TYPE(C)
LYRIC_TYPE(Don't miss that train)
BAR_LINE()
CHORD_TYPE(D)
LYRIC_TYPE(to me)
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
 
  public function testTokenizeMultipleChordAndLyricLines()
  {
    $chart = <<<EOF
G | G | C | D
Don't miss that train | comin' home | Don't miss that train | to me
G | G | C | D
We're on a plane | blowin' smoke | signin' our names | en chelo
EOF;

    $output = $this->lexer->tokenize($chart);
    $expectedOutput = <<<EOF
CHORD_TYPE(G)
LYRIC_TYPE(Don't miss that train)
BAR_LINE()
CHORD_TYPE(G)
LYRIC_TYPE(comin' home)
BAR_LINE()
CHORD_TYPE(C)
LYRIC_TYPE(Don't miss that train)
BAR_LINE()
CHORD_TYPE(D)
LYRIC_TYPE(to me)
BAR_LINE()
CHORD_TYPE(G)
LYRIC_TYPE(We're on a plane)
BAR_LINE()
CHORD_TYPE(G)
LYRIC_TYPE(blowin' smoke)
BAR_LINE()
CHORD_TYPE(C)
LYRIC_TYPE(signin' our names)
BAR_LINE()
CHORD_TYPE(D)
LYRIC_TYPE(en chelo)
EOF_TYPE()
EOF;
    $this->assertEquals($expectedOutput, (string) $output);
  }
}
