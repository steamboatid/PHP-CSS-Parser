<?php

namespace Sabberworm\CSS\RuleSet;

use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Property\AtRule;

/**
 * A RuleSet constructed by an unknown at-rule. `@font-face` rules are rendered into AtRuleSet objects.
 */
class AtRuleSet extends RuleSet implements AtRule
{
    private string $sType;

    private string $sArgs;

    /**
     * @param string $sType
     * @param string $sArgs
     * @param int $iLineNo
     */
    public function __construct($sType, $sArgs = '', $iLineNo = 0)
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
        $sArgs = $this->sArgs;
        if ($sArgs) {
            $sArgs = ' ' . $sArgs;
        }
        $sResult .= "@{$this->sType}$sArgs{$oOutputFormat->spaceBeforeOpeningBrace()}{";
        $sResult .= $this->renderRules($oOutputFormat);
        $sResult .= '}';
        return $sResult;
    }
}
