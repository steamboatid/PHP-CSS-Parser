<?php

namespace Sabberworm\CSS\Comment;

use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Renderable;

class Comment implements Renderable
{
    protected int $iLineNo;

    protected string $sComment;

    /**
     * @param string $sComment
     * @param int $iLineNo
     */
    public function __construct(string $sComment = '', int $iLineNo = 0)
    {
        $this->sComment = $sComment;
        $this->iLineNo = $iLineNo;
    }

    public function getComment(): string
    {
        return $this->sComment;
    }

    public function getLineNo(): int
    {
        return $this->iLineNo;
    }

    public function setComment(string $sComment): void
    {
        $this->sComment = $sComment;
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
        return '/*' . $this->sComment . '*/';
    }
}
