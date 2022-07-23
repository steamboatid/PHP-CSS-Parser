<?php

namespace Sabberworm\CSS\Property;

use Sabberworm\CSS\Comment\Comment;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Value\URL;

/**
 * Class representing an `@import` rule.
 */
class Import implements AtRule
{
    private URL $oLocation;

    /**
     * @var string
     */
    private ?string $sMediaQuery = null;

    protected int $iLineNo;

    /**
     * @var array<array-key, Comment>
     */
    protected array $aComments = array();

    /**
     * @param URL $oLocation
     * @param string $sMediaQuery
     * @param int $iLineNo
     */
    public function __construct(URL $oLocation, ?string $sMediaQuery=null, int $iLineNo = 0)
    {
        $this->oLocation = $oLocation;
        $this->sMediaQuery = $sMediaQuery;
        $this->iLineNo = $iLineNo;
        $this->aComments = [];
    }

    public function getLineNo(): int
    {
        return $this->iLineNo;
    }

    public function setLocation(URL $oLocation): void
    {
        $this->oLocation = $oLocation;
    }

    public function getLocation(): URL
    {
        return $this->oLocation;
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
        return $oOutputFormat->comments($this) . "@import " . $this->oLocation->render($oOutputFormat)
            . ($this->sMediaQuery === null ? '' : ' ' . $this->sMediaQuery) . ';';
    }

    public function atRuleName(): string
    {
        return 'import';
    }

    /**
     * @return mixed of CSSString|array<int, URL|string>|string|null previously as string|null
         *               in this class return as array<int, URL|string>
     */
    public function atRuleArgs()
    {
        $aResult = [$this->oLocation];
        if ($this->sMediaQuery) {
            array_push($aResult, $this->sMediaQuery);
        }
        return $aResult;
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
