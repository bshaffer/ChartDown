<?php


/**
* 
*/
class ChartDown_Lexer_Label extends ChartDown_Lexer_Base
{
  const STATE_LABEL = 0;
  
  public function doTokenize($code)
  {
    $this->state = self::STATE_LABEL;

    while ($this->cursor < $this->end) {
        // dispatch to the lexing functions depending
        // on the current state
        switch ($this->state) {
            case self::STATE_LABEL:
                $this->lexLabel();
                break;
        }
    }
  }
  
  public function lexLabel()
  {
      $pos = $this->end;

      if (false !== ($tmpPos = strpos($this->code, $this->options['bar_line'], $this->cursor))  && $tmpPos < $pos) {
          $pos = $tmpPos;
          $token = $this->options['bar_line'];
      }

      // if no matches are left we return the rest of the template as simple text token
      if ($pos === $this->end) {
          $this->pushToken(ChartDown_Token::LABEL_TYPE, trim(substr($this->code, $this->cursor)));
          $this->cursor = $this->end;
          return;
      }

      // push the template text first
      $text = substr($this->code, $this->cursor, $pos - $this->cursor);
      $this->pushToken(ChartDown_Token::LABEL_TYPE, trim($text));
      $this->pushToken(ChartDown_Token::BAR_LINE);

      switch ($token) {
          case $this->options['bar_line']:
              $this->moveCursor($text.$token);
              break;
      }
  }
}
