<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\OutputFormat;

class CalcRuleValueList extends RuleValueList
{
    /**
     * @param int $iLineNo
     */
    public function __construct($iLineNo = 0)
    {
        parent::__construct(',', $iLineNo);
    }

    public function render(OutputFormat $oOutputFormat): string
    {
        return $oOutputFormat->implode(' ', $this->aComponents);
    }
}
