<?php

class ChartDown_Tests_Lexer_MetadataTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->lexer = new ChartDown_Lexer($chartdown);
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
}
