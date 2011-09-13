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
 * Represents a Token.
 *
 * @package chartdown
 * @author  Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ChartDown_Token
{
    protected $value;
    protected $type;
    protected $lineno;

    const EOF_TYPE               = -1;
    const CHORD_TYPE             = 1;
    const CHORD_GROUP_START_TYPE = 8;
    const CHORD_GROUP_END_TYPE   = 9;
    const LINE_START             = 10;
    const LINE_END               = 11;
    const TEXT_TYPE              = 2;
    const BAR_LINE               = 3;
    const METADATA_KEY_TYPE      = 4;
    const METADATA_VALUE_TYPE    = 5;
    const EXPRESSION_TYPE        = 12;
    const END_ROW_TYPE           = 7;

    /**
     * Constructor.
     *
     * @param integer $type   The type of the token
     * @param string  $value  The token value
     * @param integer $lineno The line position in the source
     */
    public function __construct($type, $value, $lineno)
    {
        $this->type   = $type;
        $this->value  = $value;
        $this->lineno = $lineno;
    }

    /**
     * Returns a string representation of the token.
     *
     * @return string A string representation of the token
     */
    public function __toString()
    {
        return sprintf('%s(%s)', self::typeToString($this->type, true, $this->lineno), self::valueToString($this->type, $this->value));
    }

    /**
     * Tests the current token for a type and/or a value.
     *
     * Parameters may be:
     * * just type
     * * type and value (or array of possible values)
     * * just value (or array of possible values) (NAME_TYPE is used as type)
     *
     * @param array|integer     $type   The type to test
     * @param array|string|null $values The token value
     *
     * @return Boolean
     */
    public function test($type, $values = null)
    {
        if (null === $values && !is_int($type)) {
            $values = $type;
            $type = self::NAME_TYPE;
        }

        return ($this->type === $type) && (
            null === $values ||
            (is_array($values) && in_array($this->value, $values)) ||
            $this->value == $values
        );
    }

    /**
     * Gets the line.
     *
     * @return integer The source line
     */
    public function getLine()
    {
        return $this->lineno;
    }

    /**
     * Gets the token type.
     *
     * @return integer The token type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the token value.
     *
     * @return string The token value
     */
    public function getValue()
    {
        return $this->value;
    }

    static public function valueToString($type, $value)
    {
        switch ($type) {
            case self::LINE_START:
                return ChartDown_Lexer::stateToString($value);
        }
        
        return (string) $value;
    }

    /**
     * Returns the constant representation (internal) of a given type.
     *
     * @param integer $type  The type as an integer
     * @param Boolean $short Whether to return a short representation or not
     *
     * @return string The string representation
     */
    static public function typeToString($type, $short = false, $line = -1)
    {
        switch ($type) {
            case self::EOF_TYPE:
                $name = 'EOF_TYPE';
                break;
            case self::CHORD_TYPE:
                $name = 'CHORD_TYPE';
                break;
            case self::CHORD_GROUP_START_TYPE:
                $name = 'CHORD_GROUP_START_TYPE';
                break;
            case self::CHORD_GROUP_END_TYPE:
                $name = 'CHORD_GROUP_END_TYPE';
                break;
            case self::EXPRESSION_TYPE:
                $name = 'EXPRESSION_TYPE';
                break;
            case self::TEXT_TYPE:
                $name = 'TEXT_TYPE';
                break;
            case self::METADATA_KEY_TYPE:
                $name = 'METADATA_KEY_TYPE';
                break;
            case self::METADATA_VALUE_TYPE:
                $name = 'METADATA_VALUE_TYPE';
                break;
            case self::BAR_LINE:
                $name = 'BAR_LINE';
                break;
            case self::LINE_START:
                $name = 'LINE_START';
                break;
            case self::LINE_END:
                $name = 'LINE_END';
                break;
            case self::END_ROW_TYPE:
                $name = 'END_ROW_TYPE';
                break;
            default:
                throw new ChartDown_Error_Syntax(sprintf('Token of type "%s" does not exist.', $type), $line);
        }

        return $short ? $name : 'ChartDown_Token::'.$name;
    }

    /**
     * Returns the english representation of a given type.
     *
     * @param integer $type  The type as an integer
     * @param Boolean $short Whether to return a short representation or not
     *
     * @return string The string representation
     */
    static public function typeToEnglish($type, $line = -1)
    {
        switch ($type) {
            case self::EOF_TYPE:
                return 'end of template';
            case self::CHORD_TYPE:
                return 'chord';
            case self::CHORD_GROUP_START_TYPE:
                $name = 'chord group start';
                break;
            case self::CHORD_GROUP_END_TYPE:
                $name = 'chord group end';
                break;
            case self::EXPRESSION_TYPE:
                return 'expression';
            case self::TEXT_TYPE:
                return 'text';
            case self::METADATA_KEY_TYPE:
                return 'key for metadata';
            case self::METADATA_VALUE_TYPE:
                return 'value for metadata';
            case self::BAR_LINE:
                return 'bar line';
            case self::END_ROW_TYPE:
                return 'end of row';

            default:
                throw new ChartDown_Error_Syntax(sprintf('Token of type "%s" does not exist.', $type), $line);
        }
    }
}
