<?php

namespace Sabberworm\CSS\Property;

use Sabberworm\CSS\Comment\Comment;
use Sabberworm\CSS\OutputFormat;

/**
 * `CSSNamespace` represents an `@namespace` rule.
 */
class CSSNamespace implements AtRule
{
    private $mUrl;

    private ?string $sPrefix = null;

    private int $iLineNo;

    /**
     * @var array<array-key, Comment>
     */
    protected $aComments;

    /**
     * @param $mUrl
     * @param string|null $sPrefix
     * @param int $iLineNo
     */
    public function __construct($mUrl, ?string $sPrefix = null, int $iLineNo = 0)
    {
        $this->mUrl = $mUrl;
        $this->sPrefix = $sPrefix;
        $this->iLineNo = $iLineNo;
        $this->aComments = [];
    }

    public function getLineNo(): int
    {
        return $this->iLineNo;
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
        return '@namespace ' . ($this->sPrefix === null ? '' : $this->sPrefix . ' ')
            . $this->mUrl->render($oOutputFormat) . ';';
    }

    public function getUrl(): string
    {
        return $this->mUrl;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->sPrefix;
    }

    public function setUrl(string $mUrl): void
    {
        $this->mUrl = $mUrl;
    }

    public function setPrefix(string $sPrefix): void
    {
        $this->sPrefix = $sPrefix;
    }

    public function atRuleName(): string
    {
        return 'namespace';
    }

    /**
     * @return mixed of CSSString|array<int, URL|string>|string|null previously as string|null
         *               in this class return as array<int, string>
     */
    public function atRuleArgs()
    {
        $aResult = [$this->mUrl];
        if ($this->sPrefix) {
            array_unshift($aResult, $this->sPrefix);
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
