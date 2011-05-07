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
    protected $bars;
    protected $code;
    protected $state;
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

    const STATE_CHORD       = 0;
    const STATE_LYRIC       = 1;
    const STATE_EXPRESSION  = 2;
    const STATE_METADATA    = 4;
    const STATE_LABEL       = 5;
    const STATE_RHYTHM      = 6;

    const REGEX_CHORD       = '/[a-gA-G1-7][\w\-]*/';
    const REGEX_METADATA    = '/#*(.*):(.*)/';

    public function __construct(ChartDown_Environment $env, array $options = array())
    {
        $this->env = $env;

        $this->options = array_merge(array(
            'bar_delimiter'        => '|',
            'chord_group'          => array('[', ']'),
            'row_delimiter'        => '--',
            'lines_in_row_default' => 3,
        ), $options);
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

        $this->filename = $filename;
        $this->lineno = 1;
        $this->linesInRow = $this->options['lines_in_row_default'];
        $this->tokens = array();

        while ($line = $this->needle->getNextLine()) {

            if(is_null($this->state = $this->determineState($line))) {
                // Skip this line - row break
                continue;
            }

            $this->pushToken(ChartDown_Token::LINE_START, $this->state);

            // dispatch to the lexing functions depending
            // on the current state
            switch ($this->state) {
                case self::STATE_CHORD:
                    $this->lexChord($line);
                    $this->lineno++;
                    break;

                case self::STATE_LYRIC:
                    $this->lexLyric($line);
                    $this->lineno++;
                    break;

                case self::STATE_LABEL:
                    $this->lexLabel($line);
                    $this->lineno++;
                    break;

                case self::STATE_METADATA:
                    $this->lexMetadata($line);
                    $this->lineno++;
                    break;

                default:
                    throw new ChartDown_Error_Runtime(sprintf('Unsupported state "%s" - cannot tokenize', $this->state));

            }

            $this->pushToken(ChartDown_Token::LINE_END);
        }

        $this->pushToken(ChartDown_Token::EOF_TYPE);

        if (isset($mbEncoding)) {
            mb_internal_encoding($mbEncoding);
        }

        return new ChartDown_TokenStream($this->tokens, $this->filename);
    }

    public function lexChord($line)
    {
        foreach ($line->split($this->options['bar_delimiter']) as $i => $bar) {

            while (!$bar->isEOF()) {
                if (false !== ($data = $bar->moveToFirst(array(self::REGEX_CHORD, $this->options['chord_group'][0], $this->options['chord_group'][1])))) {
                    $token = $data[0];
                    $text  = $data[1];

                    if ($token == $this->options['chord_group'][0]) {
                        if (trim($text) !== $token) {
                            throw new ChartDown_Error_Syntax('Unidentified token: '.$text, $this->lineno, $this->filename);
                        }

                        $this->pushToken(ChartDown_Token::CHORD_GROUP_START_TYPE);

                    } else if ($token == $this->options['chord_group'][1]) {
                        if (trim($text) !== $token) {
                            throw new ChartDown_Error_Syntax('Unidentified token: '.$text, $this->lineno, $this->filename);
                        }

                        $this->pushToken(ChartDown_Token::CHORD_GROUP_END_TYPE);

                    } else {
                        $this->pushToken(ChartDown_Token::CHORD_TYPE, trim($text));
                    }
                } else if ($text = trim($bar->rest())) {
                    throw new ChartDown_Error_Syntax('Unidentified token: '.$text, $this->lineno, $this->filename);
                }
            }

            $this->pushToken(ChartDown_Token::BAR_LINE);
        }

        $this->popToken();
    }

    protected function lexLyric($line)
    {
        foreach ($line->split($this->options['bar_delimiter']) as $bar) {
            $this->pushToken(ChartDown_Token::LYRIC_TYPE, $bar->getText());

            $this->pushToken(ChartDown_Token::BAR_LINE);
        }

        $this->popToken();
    }

    protected function lexLabel($line)
    {
        foreach ($line->split($this->options['bar_delimiter']) as $bar) {
            $this->pushToken(ChartDown_Token::LABEL_TYPE, $bar->getText());

            $this->pushToken(ChartDown_Token::BAR_LINE);
        }

        $this->popToken();
    }

    protected function lexMetadata($line)
    {
        if ($matches = $line->match(self::REGEX_METADATA)) {
            $this->pushToken(ChartDown_Token::METADATA_KEY_TYPE, trim($matches[0]));
            $this->pushToken(ChartDown_Token::METADATA_VALUE_TYPE, trim($matches[1]));
        }

        $line->moveToEnd();
    }

    protected function determineState($line)
    {
      $text = $line->getText();

      // Determine using the line itself
      if (strlen($text) > 0) {
        if($text[0] === '#') {
          return self::STATE_METADATA;
        }
      }
      
      if (trim($text) == $this->options['row_delimiter']) {
          $this->pushToken(ChartDown_Token::END_ROW_TYPE);
          return null;
      }

      // The line is new, start from first position in line defaults
      if ($this->state === null || $this->state === self::STATE_METADATA) {
        $this->linesInRow = $this->determineLinesInRow();
        return $this->lineOrderDefaults[$this->linesInRow][0];
      }

      // Move to the next line in the order
      $pos = array_search($this->state, $this->lineOrderDefaults[$this->linesInRow]);

      if (!isset($this->lineOrderDefaults[$this->linesInRow][$pos+1])) {
          $this->pushToken(ChartDown_Token::END_ROW_TYPE);
          return $this->lineOrderDefaults[$this->linesInRow][0];
      }

      return $this->lineOrderDefaults[$this->linesInRow][$pos+1];
    }

    protected function determineLinesInRow()
    {
        $pos = $this->needle->nextMatch(sprintf('/\n?%s/', $this->options['row_delimiter']));

        if ($pos === false) {
            // Number of lines remaining (plus 1, to account for the current line)
            $numLines = $this->needle->getNumLines() + 1;
        } else {
            // Grab number of lines between this line and the next line marker (plus 2, to account for the current line and final line)
            $cursor = $this->needle->getCursor();
            $numLines = substr_count(substr($this->needle->getText(), $cursor, $pos - $cursor), "\n") + 2;
        }

        if($numLines > count($this->lineOrderDefaults) || $numLines == 0) {
            // numLines is invalid - too many lines
            $numLines = $this->options['lines_in_row_default'];
        }

        return $numLines;
    }

    protected function pushToken($type, $value = '')
    {
        $this->tokens[] = new ChartDown_Token($type, $value, $this->lineno);
    }

    protected function popToken($type = null)
    {
        array_pop($this->tokens);
    }

    static public function stateToString($state, $short = true, $lineno = -1)
    {
        switch ($state) {
            case self::STATE_CHORD:
                $name = 'STATE_CHORD';
                break;
            case self::STATE_LYRIC:
                $name = 'STATE_LYRIC';
                break;
            case self::STATE_EXPRESSION:
                $name = 'STATE_EXPRESSION';
                break;
            case self::STATE_METADATA:
                $name = 'STATE_METADATA';
                break;
            case self::STATE_LABEL:
                $name = 'STATE_LABEL';
                break;
            case self::STATE_RHYTHM:
                $name = 'STATE_RHYTHM';
                break;
            default:
                throw new ChartDown_Error_Syntax(sprintf('State of type "%s" does not exist.', $state), $line);
        }

        return $short ? $name : 'ChartDown_Lexer::'.$name;
    }
}
