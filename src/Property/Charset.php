<?php

namespace Sabberworm\CSS\Property;

use Sabberworm\CSS\Comment\Comment;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Value\CSSString;

/**
 * Class representing an `@charset` rule.
 *
 * The following restrictions apply:
 * - May not be found in any CSSList other than the Document.
 * - May only appear at the very top of a Document’s contents.
 * - Must not appear more than once.
 */
class Charset implements AtRule
{
    private CSSString $oCharset;

    protected int $iLineNo;

    /**
     * @var array<array-key, Comment>
     */
    protected $aComments;

    /**
     * @param CSSString $oCharset
     * @param int $iLineNo
     */
    public function __construct(CSSString $oCharset, int $iLineNo = 0)
    {
        $this->oCharset = $oCharset;
        $this->iLineNo = $iLineNo;
        $this->aComments = [];
    }

    public function getLineNo(): int
    {
        return $this->iLineNo;
    }

    /**
     * @param mixed $oCharset previously as string|CSSString
     */
    public function setCharset(mixed $sCharset): void
    {
        $sCharset = $sCharset instanceof CSSString ? $sCharset : new CSSString($sCharset);
        $this->oCharset = $sCharset;
    }

    public function getCharset(): string
    {
        return $this->oCharset->getString();
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
        return "{$oOutputFormat->comments($this)}@charset {$this->oCharset->render($oOutputFormat)};";
    }

    public function atRuleName(): string
    {
        return 'charset';
    }

    /**
     * @return mixed of CSSString|array<int, URL|string>|string|null previously as string|null
     *               in this class return as string
     */
    public function atRuleArgs()
    {
        return $this->oCharset;
    }

    /**
     * @param array<array-key, Comment> $aComments
     */
    public function addComments(array $aComments): void
    {
        $this->aComments = array_merge($this->aComments, $aComments);
    }

    /**
     * @return array<array-key, Comment>
     */
    public function getComments(): array
    {
        return $this->aComments;
    }

    /**
     * @param array<array-key, Comment> $aComments
     */
    public function setComments(array $aComments): void
    {
        $this->aComments = $aComments;
    }
}
