<?php

namespace Sabberworm\CSS\Value;

class RuleValueList extends ValueList
{
    /**
     * @param string $sSeparator
     * @param int $iLineNo
     */
    public function __construct(string $sSeparator = ',', int $iLineNo = 0)
    {
        parent::__construct([], $sSeparator, $iLineNo);
    }
}
