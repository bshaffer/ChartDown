<?php


/**
*
*/
class ChartDown_Lexer_Chord extends ChartDown_Lexer_Base
{
  const STATE_CHORD = 0;
  const STATE_GROUP = 1;

  public function __construct(ChartDown_Environment $env, array $options = array())
  {
      $options = array_merge(array(
          'tag_group'    => array('[', ']'),
      ), $options);
    
      parent::__construct($env, $options);
  }


  public function doTokenize($code)
  {
    $this->state = self::STATE_CHORD;

    while ($this->cursor < $this->end) {
        // dispatch to the lexing functions depending
        // on the current state
        switch ($this->state) {
            case self::STATE_CHORD:
                $this->lexChord();
                break;

            case self::STATE_GROUP:
                $this->lexGroup();
                break;
        }
    }
  }

  public function lexChord()
  {
      $pos = $this->end;
      
      if ($match = $this->needle->match(self::CHORD_REGEX)) {
        # code...
      }
      if (false !== ($tmpPos = strpos($this->code, $this->options['bar_line'], $this->cursor))  && $tmpPos < $pos) {
          $pos = $tmpPos;
          $token = $this->options['bar_line'];
      }

      // if no matches are left we return the rest of the template as simple text token
      if ($pos === $this->end) {
          $this->pushToken(ChartDown_Token::CHORD_TYPE, trim(substr($this->code, $this->cursor)));
          $this->cursor = $this->end;
          return;
      }

      // push the template text first
      $text = substr($this->code, $this->cursor, $pos - $this->cursor);
      $this->pushToken(ChartDown_Token::CHORD_TYPE, trim($text));
      $this->pushToken(ChartDown_Token::BAR_LINE);

      switch ($token) {
          case $this->options['bar_line']:
              $this->moveCursor($text.$token);
              break;
              
          case $this->options['tag_group'][0]:
              if (false === $pos = strpos($this->code, $this->options['tag_group'][1], $this->cursor)) {
                  throw new ChartDown_Error_Syntax('unclosed chord group.  Expected ending "]"', $this->lineno, $this->filename);
              }

              $this->moveCursor(substr($this->code, $this->cursor, $pos - $this->cursor) . $this->options['tag_group'][1]);
              $this->state = self::STATE_GROUP;

              break;

      }
  }
}
