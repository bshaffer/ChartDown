<?php

class ChartDown_Tests_CompilerTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $chartdown = new ChartDown_Environment();
    $this->compiler = new ChartDown_Compiler($chartdown);
  }

  public function testCompileChordAndLyricNodes()
  {
    // Every country song ever written
    $node = new ChartDown_Node_Module(
      new ChartDown_Node(array(
          new ChartDown_Node_Chord('G', -1),
          new ChartDown_Node_Lyric('That old dusty wind', -1),
    )),
     null,
     new ChartDown_Node(),
     new ChartDown_Node(),
     null);

    $this->compiler->compile($node);
    
    $source = $this->compiler->getSource();
    
    $this->assertContains('->addChord("G")', $source);
    $this->assertContains('->addLyric("That old dusty wind")', $source);
  }
  
  public function testCompileMetadataNode()
  {
    // Every country song ever written
    $node = new ChartDown_Node_Module(
      new ChartDown_Node(array(
          new ChartDown_Node_Metadata('title', 'Every Country Song Ever Written'),
    )),
     null,
     new ChartDown_Node(),
     new ChartDown_Node(),
     null);

    $this->compiler->compile($node);
    
    $source = $this->compiler->getSource();
    
    $this->assertContains('->setTitle("Every Country Song Ever Written")', $source);
  }
  
  public function testCompileMetadataWithTimeSignature()
  {
    // Every country song ever written
    $node = new ChartDown_Node_Module(
      new ChartDown_Node(array(
          new ChartDown_Node_Metadata('time signature', '6/5'),
    )),
     null,
     new ChartDown_Node(),
     new ChartDown_Node(),
     null);

    $this->compiler->compile($node);
    
    $source = $this->compiler->getSource();

    $this->assertContains('->setTimeSignature(new ChartDown_Chart_TimeSignature(6, 5))', $source);
  }
  
  public function testCompileMetadataWithKeySignature()
  {
    // Every country song ever written
    $node = new ChartDown_Node_Module(
      new ChartDown_Node(array(
          new ChartDown_Node_Metadata('key signature', 'G'),
    )),
     null,
     new ChartDown_Node(),
     new ChartDown_Node(),
     null);

    $this->compiler->compile($node);
    
    $source = $this->compiler->getSource();

    $this->assertContains('->setKey(new ChartDown_Chart_Key("G"))', $source);
  }
  
  public function testCompileChordAndLyricAndMetadataNodes()
  {
    // Every country song ever written
    $node = new ChartDown_Node_Module(
      new ChartDown_Node(array(
          new ChartDown_Node_Metadata('title', 'Every Country Song Ever Written'),
          new ChartDown_Node_Chord('G', -1),
          new ChartDown_Node_Lyric('That old dusty wind', -1),
    )),
     null,
     new ChartDown_Node(),
     new ChartDown_Node(),
     null);

    $this->compiler->compile($node);
    
    $source = $this->compiler->getSource();
    
    $this->assertContains('->setTitle("Every Country Song Ever Written")', $source);
    $this->assertContains('->addChord("G")', $source);
    $this->assertContains('->addLyric("That old dusty wind")', $source);
  }
  
  public function testCompileChordGroupNodes()
  {
    // Every country song ever written
    $node = new ChartDown_Node_Module(
      new ChartDown_Node(array(
          new ChartDown_Node_Bar(array(
              new ChartDown_Node_ChordGroup(array(
                  new ChartDown_Node_Chord('C', -1),
                  new ChartDown_Node_Chord('D', -1),
              ), -1),
          ))
      )),
     null,
     new ChartDown_Node(),
     new ChartDown_Node(),
     null);

    $this->compiler->compile($node);
    
    $source = $this->compiler->getSource();
    
    $this->assertContains('->addChordGroup()', $source);
    $this->assertContains('->addChord("C")', $source);
  }
}