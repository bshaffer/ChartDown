<?php

/*
 * This file is part of ChartDown.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ChartDown_TokenParser_Chord extends ChartDown_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param ChartDown_Token $token A ChartDown_Token instance
     *
     * @return ChartDown_NodeInterface A ChartDown_NodeInterface instance
     */
    public function parse(ChartDown_Token $token)
    {
        $macro = $this->parser->getExpressionParser()->parseExpression();
        $this->parser->getStream()->expect('G');
        $var = new ChartDown_Node_Expression_AssignName($this->parser->getStream()->expect(ChartDown_Token::NAME_TYPE)->getValue(), $token->getLine());
        $this->parser->getStream()->expect(ChartDown_Token::BLOCK_END_TYPE);

        return new ChartDown_Node_Chord($macro, $var, $token->getLine(), $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    public function getTag()
    {
        return 'chord';
    }
}
