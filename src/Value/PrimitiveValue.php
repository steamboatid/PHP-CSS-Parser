<?php

namespace Sabberworm\CSS\Value;

abstract class PrimitiveValue extends Value
{
    /**
     * @param int $iLineNo
     */
    public function __construct(int $iLineNo = 0)
    {
        parent::__construct($iLineNo);
    }
}
