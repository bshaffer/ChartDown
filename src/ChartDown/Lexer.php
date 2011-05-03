<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Lexes a template string.
 *
 * @package    chartdown
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_Lexer implements ChartDown_LexerInterface
{
    protected $tokens;
    protected $rawTokens;
    protected $code;
    protected $cursor;
    protected $lineno;
    protected $end;
    protected $state;
    protected $brackets;
    protected $lines;
    protected $linesInRow;
    protected $lineOrderDefaults = array(
      1 => array(self::STATE_CHORD),
      2 => array(self::STATE_CHORD, self::STATE_LYRIC),
      3 => array(self::STATE_LABEL, self::STATE_CHORD, self::STATE_LYRIC),
      4 => array(self::STATE_LABEL, self::STATE_EXPRESSION, self::STATE_CHORD, self::STATE_LYRIC),
      5 => array(self::STATE_LABEL, self::STATE_RHYTHM, self::STATE_EXPRESSION, self::STATE_CHORD, self::STATE_LYRIC)
    );

    protected $env;
    protected $filename;
    protected $options;
    protected $operatorRegex;

    protected $chordLexer;
    protected $lyricLexer;
    protected $labelLexer;
    protected $metadataLexer;
    protected $expressionLexer;

    const STATE_CHORD       = 0;
    const STATE_LYRIC       = 1;
    const STATE_EXPRESSION  = 2;
    const STATE_END_OF_LINE = 3;
    const STATE_METADATA    = 4;
    const STATE_LABEL       = 5;
    const STATE_RHYTHM      = 6;

    const REGEX_CHORD       = '/[a-gA-G1-7]\w*/';

    public function __construct(ChartDown_Environment $env, array $options = array())
    {
        $this->env = $env;

        $this->options = array_merge(array(
            'bar_line'    => '|',
        ), $options);
    }

    public function getChordLexer()
    {
      if ($this->chordLexer === null) {
        $this->chordLexer = new ChartDown_Lexer_Chord($this->env, $this->options);
      }

      return $this->chordLexer;
    }

    public function getLyricLexer()
    {
      if ($this->lyricLexer === null) {
        $this->lyricLexer = new ChartDown_Lexer_Lyric($this->env, $this->options);
      }

      return $this->lyricLexer;
    }

    public function getLabelLexer()
    {
      if ($this->labelLexer === null) {
        $this->labelLexer = new ChartDown_Lexer_Label($this->env, $this->options);
      }

      return $this->labelLexer;
    }

    public function getMetadataLexer()
    {
      if ($this->metadataLexer === null) {
        $this->metadataLexer = new ChartDown_Lexer_Metadata($this->env, $this->options);
      }

      return $this->metadataLexer;
    }

    /**
     * Tokenizes a source code.
     *
     * @param  string $code     The source code
     * @param  string $filename A unique identifier for the source code
     *
     * @return ChartDown_TokenStream A token stream instance
     */
    public function tokenize($code, $filename = null)
    {
        $this->needle = new ChartDown_LexerNeedle($code);

        if (function_exists('mb_internal_encoding') && ((int) ini_get('mbstring.func_overload')) & 2) {
            $mbEncoding = mb_internal_encoding();
            mb_internal_encoding('ASCII');
        }

        $this->lines = explode("\n", str_replace(array("\r\n", "\r"), "\n", $code));
        $this->linesInRow = 3;
        $this->filename = $filename;
        $this->lineno = 1;
        $this->end = count($this->lines);
        $this->tokens = array();
        $this->rawTokens = array();
        $this->brackets = array();

        while (!$this->needle->isEOF()) {
            $this->state = $this->determineState();

            // dispatch to the lexing functions depending
            // on the current state
            switch ($this->state) {
                case self::STATE_CHORD:
                    $this->lexChord();
                    $this->lineno++;
                    break;

                case self::STATE_LYRIC:
                    $this->lexLyric();
                    $this->lineno++;
                    break;

                case self::STATE_LABEL:
                    $this->lexLabel();
                    $this->lineno++;
                    break;

                case self::STATE_METADATA:
                    $this->lexMetadata();
                    $this->lineno++;
                    break;

                case self::STATE_END_OF_LINE:
                    $this->compileLine();
                    $this->pushToken(ChartDown_Token::END_ROW_TYPE);
                    break;

                default:
                    throw new ChartDown_Runtime_Exception('Unsupported state "%s" - cannot tokenize', $this->state);

            }
        }

        if (count($this->rawTokens > 0)) {
          $this->compileLine();
        }

        $this->pushToken(ChartDown_Token::EOF_TYPE);

        if (!empty($this->brackets)) {
            list($expect, $lineno) = array_pop($this->brackets);
            throw new ChartDown_Error_Syntax(sprintf('Unclosed "%s"', $expect), $lineno, $this->filename);
        }

        if (isset($mbEncoding)) {
            mb_internal_encoding($mbEncoding);
        }

        return new ChartDown_TokenStream($this->tokens, $this->filename);
    }

    public function lexChord()
    {
        $pos = $this->end;

        if (false !== ($tmpPos = strpos($this->code, $this->options['bar_line'], $this->cursor))  && $tmpPos < $pos) {
            $pos = $tmpPos;
            $token = $this->options['bar_line'];
        }

        if (false !== ($tmpPos = strpos($this->code, $this->options['tag_group'][0], $this->cursor))  && $tmpPos < $pos) {
            $pos = $tmpPos;
            $token = $this->options['tag_group'][0];
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
    protected function lexLyric()
    {
      $stream = $this->getLyricLexer()->tokenize($this->lines[$this->lineno -1], $this->filename);
      $this->pushStream($stream);
    }

    protected function lexLabel()
    {
      $stream = $this->getLabelLexer()->tokenize($this->lines[$this->lineno -1], $this->filename);
      $this->pushStream($stream);
    }

    protected function lexMetadata()
    {
      $stream = $this->getMetadataLexer()->tokenize($this->lines[$this->lineno -1], $this->filename);
      $this->pushStream($stream);
    }

    protected function determineState()
    {
      $line = $this->getCurrentLine();

      // Determine using the line itself
      if (strlen($line) > 0) {
        if($line[0] === '#') {
          return self::STATE_METADATA;
        }
      }

      // The line is new, start from first position in line defaults
      if ($this->state === null || $this->state === self::STATE_END_OF_LINE || $this->state === self::STATE_METADATA) {
        return $this->lineOrderDefaults[$this->linesInRow][0];
      }

      // The line is midway, use line order defaults
      $pos = array_search($this->state, $this->lineOrderDefaults[$this->linesInRow]);

      return isset($this->lineOrderDefaults[$this->linesInRow][$pos+1]) ? $this->lineOrderDefaults[$this->linesInRow][$pos+1] : self::STATE_END_OF_LINE;
    }

    protected function getCurrentLine()
    {
      return $this->lines[$this->lineno-1];
    }

    protected function pushStream(ChartDown_TokenStream $stream)
    {
      $i = 0;
      foreach ($stream->getTokens() as $token) {
        if ($token->getType() === ChartDown_Token::BAR_LINE) {
          $i++;
          continue;
        }

        if (!isset($this->rawTokens[$i])) {
          $this->rawTokens[$i] = array();
        }

        $this->rawTokens[$i][] = $token;
      }
    }

    protected function compileLine()
    {
      foreach ($this->rawTokens as $i => $bar) {
        foreach ($bar as $token) {
          $this->tokens[] = $token;
        }

        if ($i >= count($this->rawTokens) -1) {
          break;
        }

        $this->pushToken(ChartDown_Token::BAR_LINE);
      }

      $this->rawTokens = array();
    }
  
    protected function pushToken($type, $value = '')
    {
        $this->tokens[] = new ChartDown_Token($type, $value, $this->lineno);
    }

    protected function moveCursor($text)
    {
        $this->cursor += strlen($text);
        $this->lineno += substr_count($text, "\n");
    }
}
