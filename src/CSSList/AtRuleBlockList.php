<?php

namespace Sabberworm\CSS\CSSList;

use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Property\AtRule;

/**
 * A `BlockList` constructed by an unknown at-rule. `@media` rules are rendered into `AtRuleBlockList` objects.
 */
class AtRuleBlockList extends CSSBlockList implements AtRule
{
    private string $sType;

    private string $sArgs;

    /**
     * @param string $sType
     * @param string $sArgs
     * @param int $iLineNo
     */
    public function __construct(string $sType, string $sArgs = '', int $iLineNo = 0)
    {
        parent::__construct($iLineNo);
        $this->sType = $sType;
        $this->sArgs = $sArgs;
    }

    public function atRuleName(): string
    {
        return $this->sType;
    }

    /**
     * @return mixed of CSSString|array<int, URL|string>|string|null previously as string|null
     *               in this class return as string
     */
    public function atRuleArgs()
    {
        return $this->sArgs;
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
        $sResult .= $oOutputFormat->sBeforeAtRuleBlock;
        $sArgs = $this->sArgs;
        if ($sArgs) {
            $sArgs = ' ' . $sArgs;
        }
        $sResult .= "@{$this->sType}$sArgs{$oOutputFormat->spaceBeforeOpeningBrace()}{";
        $sResult .= $this->renderListContents($oOutputFormat);
        $sResult .= '}';
        $sResult .= $oOutputFormat->sAfterAtRuleBlock;
        return $sResult;
    }

    public function isRootList(): bool
    {
        return false;
    }
}
