<?php

class ChartDown_Tests_ParserTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->parser = new ChartDown_Parser($chartdown);
  }

  public function testParseTokenStream()
  {
    // Every country song ever written
    $stream = new ChartDown_TokenStream(array(
      new ChartDown_Token(ChartDown_Token::LINE_START, ChartDown_Token::CHORD_TYPE, -1),
      new ChartDown_Token(ChartDown_Token::CHORD_TYPE, 'G', -1),
      new ChartDown_Token(ChartDown_Token::BAR_LINE, null, -1),
      new ChartDown_Token(ChartDown_Token::CHORD_TYPE, 'C', -1),
      new ChartDown_Token(ChartDown_Token::BAR_LINE, null, -1),
      new ChartDown_Token(ChartDown_Token::CHORD_TYPE, 'D', -1),
      new ChartDown_Token(ChartDown_Token::BAR_LINE, null, -1),
      new ChartDown_Token(ChartDown_Token::CHORD_TYPE, 'C', -1),
      new ChartDown_Token(ChartDown_Token::LINE_END, null, -1),
      new ChartDown_Token(ChartDown_Token::LINE_START, ChartDown_Token::LYRIC_TYPE, -1),
      new ChartDown_Token(ChartDown_Token::LYRIC_TYPE, 'That old dusty wind', -1),
      new ChartDown_Token(ChartDown_Token::BAR_LINE, null, -1),
      new ChartDown_Token(ChartDown_Token::LYRIC_TYPE, 'Blows through my bones', -1),
      new ChartDown_Token(ChartDown_Token::BAR_LINE, null, -1),
      new ChartDown_Token(ChartDown_Token::LYRIC_TYPE, 'My whole life changed', -1),
      new ChartDown_Token(ChartDown_Token::BAR_LINE, null, -1),
      new ChartDown_Token(ChartDown_Token::LYRIC_TYPE, 'When we were attacked by those clones', -1),
      new ChartDown_Token(ChartDown_Token::LINE_END, null, -1),
      new ChartDown_Token(ChartDown_Token::EOF_TYPE, null, -1),
    ));

    $node = $this->parser->parse($stream);

    $this->assertTrue($node->hasNode('body'));
    $this->assertEquals($node->getNode('body')->count(), 4);
    $this->assertEquals($node->getNode('body')->getNode(0)->count(), 2);
    $this->assertEquals($node->getNode('body')->getNode(0)->getNode(1)->getAttribute('data'), 'That old dusty wind');
  }

  public function testParseTokenStreamMetadata()
  {
    // Every country song ever written
    $stream = new ChartDown_TokenStream(array(
      new ChartDown_Token(ChartDown_Token::METADATA_KEY_TYPE, 'title', -1),
      new ChartDown_Token(ChartDown_Token::METADATA_VALUE_TYPE, 'My Old Kentucky Clone', -1),
      new ChartDown_Token(ChartDown_Token::EOF_TYPE, null, -1),
    ));

    $node = $this->parser->parse($stream);

    $this->assertTrue($node->hasNode('body'));
    $this->assertEquals($node->getNode('body')->getNode(0)->getAttribute('name'), 'title');
    $this->assertEquals($node->getNode('body')->getNode(0)->getAttribute('value'), 'My Old Kentucky Clone');
  }


  /**
  * @expectedException ChartDown_Error_Syntax
  */
  public function testChordGroupWithNoChordsThrowException()
  {
    $stream = new ChartDown_TokenStream(array(
      new ChartDown_Token(ChartDown_Token::CHORD_GROUP_START_TYPE, '[', -1),
      new ChartDown_Token(ChartDown_Token::CHORD_GROUP_END_TYPE, ']', -1),
    ));

    $this->parser->parse($stream);
  }
}