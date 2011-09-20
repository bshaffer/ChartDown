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

    protected $env;
    protected $filename;
    protected $options;
    protected $expressionRegex;

    const STATE_CHORD       = 0;
    const STATE_TEXT        = 1;
    const STATE_METADATA    = 4;

    const REGEX_CHORD       = '/[a-gA-G1-7][A-G|m|M|b|#|+|2|7|9|11|13|sus|dim|add|\(|\)\/]*/';
    const REGEX_METADATA    = '/#*(.*):(.*)/';

    public function __construct(ChartDown_Environment $env, array $options = array())
    {
        $this->env = $env;

        $this->options = array_merge(array(
            'bar_delimiter'        => '|',
            'chord_group'          => array('[', ']'),
            'row_delimiter'        => '--',
        ), $options);
        
        $regex = array();
        foreach ($env->getExpressionTypes() as $type) {
            $regex[] = $type->getRegex();
        }
        
        $this->expressionRegex = sprintf('/%s/', implode('|', $regex));
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

                case self::STATE_TEXT:
                    $this->lexText($line);
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
            $bar->trim();
            while (!$bar->isEOF()) {
                if (false !== ($data = $bar->moveToFirst(array(self::REGEX_CHORD, $this->expressionRegex, $this->options['chord_group'][0], $this->options['chord_group'][1])))) {
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

                    } elseif( $token == self::REGEX_CHORD ) {
                        $this->pushToken(ChartDown_Token::CHORD_TYPE, trim($text));
                    } else {
                        $this->pushToken(ChartDown_Token::EXPRESSION_TYPE, trim($text));
                    }
                } else if ($text = trim($bar->rest())) {
                    throw new ChartDown_Error_Syntax('Unidentified token: '.$text, $this->lineno, $this->filename);
                }
            }

            $this->pushToken(ChartDown_Token::BAR_LINE);
        }

        $this->popToken();
    }

    protected function lexText($line)
    {
        $line->ltrim('text:');
        foreach ($line->split($this->options['bar_delimiter']) as $bar) {
            // trim one space off beginning and end (if it exists)
            $bar->ltrim(' ')->rtrim(' '); 
            
            // don't push empty text
            // if ($text = $bar->getText()) {
                $this->pushToken(ChartDown_Token::TEXT_TYPE, $bar->getText());
            // }

            $this->pushToken(ChartDown_Token::BAR_LINE);
        }

        // remove last bar line
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

      if(trim($text) == '') {
        return null;
      }

      // Determine using the line itself
      if(0 === strpos($text, '#')) {
        return self::STATE_METADATA;
      }
        
      if(0 === strpos($text, 'text:')) {
        return self::STATE_TEXT;
      }
      
      if (trim($text) == $this->options['row_delimiter']) {
          $this->pushToken(ChartDown_Token::END_ROW_TYPE);
          return null;
      }
      
      return self::STATE_CHORD;
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
            case self::STATE_METADATA:
                $name = 'STATE_METADATA';
                break;
            case self::STATE_TEXT:
                $name = 'STATE_TEXT';
                break;
            default:
                throw new ChartDown_Error_Syntax(sprintf('State of type "%s" does not exist.', $state), $line);
        }

        return $short ? $name : 'ChartDown_Lexer::'.$name;
    }
}
