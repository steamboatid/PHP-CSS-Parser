<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\SourceException;
use Sabberworm\CSS\Parsing\UnexpectedEOFException;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;

class URL extends PrimitiveValue
{
    private CSSString $oURL;

    /**
     * @param int $iLineNo
     */
    public function __construct(CSSString $oURL, int $iLineNo = 0)
    {
        parent::__construct($iLineNo);
        $this->oURL = $oURL;
    }

    /**
     *
     * @throws SourceException
     * @throws UnexpectedEOFException
     * @throws UnexpectedTokenException
     */
    public static function parse(ParserState $oParserState): \Sabberworm\CSS\Value\URL
    {
        $bUseUrl = $oParserState->comes('url', true);
        if ($bUseUrl) {
            $oParserState->consume('url');
            $oParserState->consumeWhiteSpace();
            $oParserState->consume('(');
        }
        $oParserState->consumeWhiteSpace();
        $oResult = new URL(CSSString::parse($oParserState), $oParserState->currentLine());
        if ($bUseUrl) {
            $oParserState->consumeWhiteSpace();
            $oParserState->consume(')');
        }
        return $oResult;
    }

    public function setURL(CSSString $oURL): void
    {
        $this->oURL = $oURL;
    }

    public function getURL(): CSSString
    {
        return $this->oURL;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render(new OutputFormat());
    }

    public function render(OutputFormat $oOutputFormat): string
    {
        return "url({$this->oURL->render($oOutputFormat)})";
    }
}
