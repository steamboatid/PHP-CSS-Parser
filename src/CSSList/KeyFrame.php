<?php

namespace Sabberworm\CSS\CSSList;

use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Property\AtRule;

class KeyFrame extends CSSList implements AtRule
{
    private ?string $vendorKeyFrame = null;

    private ?string $animationName = null;

    /**
     * @param int $iLineNo
     */
    public function __construct($iLineNo = 0)
    {
        parent::__construct($iLineNo);
        $this->vendorKeyFrame = null;
        $this->animationName = null;
    }

    public function setVendorKeyFrame(string $vendorKeyFrame): void
    {
        $this->vendorKeyFrame = $vendorKeyFrame;
    }

    /**
     * @return string|null
     */
    public function getVendorKeyFrame(): ?string
    {
        return $this->vendorKeyFrame;
    }

    public function setAnimationName(string $animationName): void
    {
        $this->animationName = $animationName;
    }

    /**
     * @return string|null
     */
    public function getAnimationName(): ?string
    {
        return $this->animationName;
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
        $sResult = $oOutputFormat->comments($this);
        $sResult .= "@{$this->vendorKeyFrame} {$this->animationName}{$oOutputFormat->spaceBeforeOpeningBrace()}{";
        $sResult .= $this->renderListContents($oOutputFormat);
        $sResult .= '}';
        return $sResult;
    }

    public function isRootList(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function atRuleName(): ?string
    {
        return $this->vendorKeyFrame;
    }

    /**
     * @return mixed of CSSString|array<int, URL|string>|string|null previously as string|null
         *               in this class return as string|null
     */
    public function atRuleArgs()
    {
        return $this->animationName;
    }
}
