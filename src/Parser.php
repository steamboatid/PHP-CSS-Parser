<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\CSSList\Document;
use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\SourceException;

/**
 * This class parses CSS from text into a data structure.
 */
class Parser
{
    private ParserState $oParserState;

    /**
     * @param string $sText
     * @param Settings|null $oParserSettings
     * @param int $iLineNo the line number (starting from 1, not from 0)
     */
    public function __construct(string $sText, ?Settings $oParserSettings = null, int $iLineNo = 1)
    {
        if ($oParserSettings === null) {
            $oParserSettings = Settings::create();
        }
        $this->oParserState = new ParserState($sText, $oParserSettings, $iLineNo);
    }

    public function setCharset(string $sCharset): void
    {
        $this->oParserState->setCharset($sCharset);
    }

    public function getCharset(): void
    {
        // Note: The `return` statement is missing here. This is a bug that needs to be fixed.
        $this->oParserState->getCharset();
    }

    /**
     * @throws SourceException
     */
    public function parse(): Document
    {
        return Document::parse($this->oParserState);
    }
}
