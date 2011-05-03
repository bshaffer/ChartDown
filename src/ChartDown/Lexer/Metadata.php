<?php


/**
*
*/
class ChartDown_Lexer_Metadata extends ChartDown_Lexer_Base
{
  const STATE_METADATA = 0;

  public function doTokenize($code)
  {
    $this->state = self::STATE_METADATA;

    while ($this->cursor < $this->end) {
        // dispatch to the lexing functions depending
        // on the current state
        switch ($this->state) {
            case self::STATE_METADATA:
                $this->lexMetadata();
                break;
        }
    }
  }

  public function lexMetadata()
  {
      $pos = $this->end;

      // Key value pair
      if (preg_match('/#*(.*):(.*)/', $this->code, $matches)) {
        $this->pushToken(ChartDown_Token::METADATA_KEY_TYPE, trim($matches[1]));
        $this->pushToken(ChartDown_Token::METADATA_VALUE_TYPE, trim($matches[2]));
      }
      
      $this->cursor = $this->end;
  }
}
