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
 * Represents a token stream.
 *
 * @package chartdown
 * @author  Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_TokenStream
{
    protected $tokens;
    protected $current;
    protected $filename;

    /**
     * Constructor.
     *
     * @param array  $tokens   An array of tokens
     * @param string $filename The name of the filename which tokens are associated with
     */
    public function __construct(array $tokens, $filename = null)
    {
        $this->tokens     = $tokens;
        $this->current    = 0;
        $this->filename   = $filename;
    }

    /**
     * Returns a string representation of the token stream.
     *
     * @return string
     */
    public function __toString()
    {
        return implode("\n", $this->tokens);
    }

    /**
     * Sets the pointer to the next token and returns the old one.
     *
     * @return ChartDown_Token
     */
    public function next()
    {
        if (!isset($this->tokens[++$this->current])) {
            throw new ChartDown_Error_Syntax('Unexpected end of template');
        }

        return $this->tokens[$this->current - 1];
    }

    /**
     * Tests a token and returns it or throws a syntax error.
     *
     * @return ChartDown_Token
     */
    public function expect($type, $value = null, $message = null)
    {
        $token = $this->tokens[$this->current];
        if (!$token->test($type, $value)) {
            $line = $token->getLine();
            throw new ChartDown_Error_Syntax(sprintf('%sUnexpected token "%s" of value "%s" ("%s" expected%s)',
                $message ? $message.'. ' : '',
                ChartDown_Token::typeToEnglish($token->getType(), $line), $token->getValue(),
                ChartDown_Token::typeToEnglish($type, $line), $value ? sprintf(' with value "%s"', $value) : ''),
                $line
            );
        }
        $this->next();

        return $token;
    }

    /**
     * Looks at the next token.
     *
     * @param integer $number
     *
     * @return ChartDown_Token
     */
    public function look($number = 1)
    {
        if (!isset($this->tokens[$this->current + $number])) {
            throw new ChartDown_Error_Syntax('Unexpected end of template');
        }

        return $this->tokens[$this->current + $number];
    }

    /**
     * Tests the current token
     *
     * @return bool
     */
    public function test($primary, $secondary = null)
    {
        return $this->tokens[$this->current]->test($primary, $secondary);
    }

    /**
     * Checks if end of stream was reached
     *
     * @return bool
     */
    public function isEOF()
    {
        return $this->tokens[$this->current]->getType() === ChartDown_Token::EOF_TYPE;
    }

    /**
     * Gets the current token
     *
     * @return ChartDown_Token
     */
    public function getCurrent()
    {
        return $this->tokens[$this->current];
    }

    /**
     * Gets the filename associated with this stream
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
    
    public function getTokens()
    {
      return $this->tokens;
    }
}
