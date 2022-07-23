<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\Comment\Commentable;
use Sabberworm\CSS\Parsing\OutputException;

class OutputFormatter
{
    private OutputFormat $oFormat;

    public function __construct(OutputFormat $oFormat)
    {
        $this->oFormat = $oFormat;
    }

    /**
     * @param string|null $sType
     *
     */
    public function space(string $sName, $sType = null): string
    {
        $sSpaceString = $this->oFormat->get("Space$sName");
        // If $sSpaceString is an array, we have multiple values configured
        // depending on the type of object the space applies to
        if (is_array($sSpaceString)) {
            if ($sType !== null && isset($sSpaceString[$sType])) {
                $sSpaceString = $sSpaceString[$sType];
            } else {
                $sSpaceString = reset($sSpaceString);
            }
        }
        return $this->prepareSpace($sSpaceString);
    }

    public function spaceAfterRuleName(): string
    {
        return $this->space('AfterRuleName');
    }

    public function spaceBeforeRules(): string
    {
        return $this->space('BeforeRules');
    }

    public function spaceAfterRules(): string
    {
        return $this->space('AfterRules');
    }

    public function spaceBetweenRules(): string
    {
        return $this->space('BetweenRules');
    }

    public function spaceBeforeBlocks(): string
    {
        return $this->space('BeforeBlocks');
    }

    public function spaceAfterBlocks(): string
    {
        return $this->space('AfterBlocks');
    }

    public function spaceBetweenBlocks(): string
    {
        return $this->space('BetweenBlocks');
    }

    public function spaceBeforeSelectorSeparator(): string
    {
        return $this->space('BeforeSelectorSeparator');
    }

    public function spaceAfterSelectorSeparator(): string
    {
        return $this->space('AfterSelectorSeparator');
    }

    public function spaceBeforeListArgumentSeparator(string $sSeparator): string
    {
        return $this->space('BeforeListArgumentSeparator', $sSeparator);
    }

    public function spaceAfterListArgumentSeparator(string $sSeparator): string
    {
        return $this->space('AfterListArgumentSeparator', $sSeparator);
    }

    public function spaceBeforeOpeningBrace(): string
    {
        return $this->space('BeforeOpeningBrace');
    }

    /**
     * Runs the given code, either swallowing or passing exceptions, depending on the `bIgnoreExceptions` setting.
     *
     * @param mixed $cCode the name of the function to call
     *
     * @return string|null
     */
    public function safely($cCode)
    {
        if ($this->oFormat->get('IgnoreExceptions')) {
            // If output exceptions are ignored, run the code with exception guards
            try {
                return $cCode();
            } catch (OutputException $e) {
                return null;
            } // Do nothing
        } else {
            // Run the code as-is
            return $cCode();
        }
    }

    /**
     * Clone of the `implode` function, but calls `render` with the current output format instead of `__toString()`.
     *
     * @param array<array-key, Renderable|string> $aValues
     *
     */
    public function implode(string $sSeparator, array $aValues, bool $bIncreaseLevel = false): string
    {
        $sResult = '';
        $oFormat = $this->oFormat;
        if ($bIncreaseLevel) {
            $oFormat = $oFormat->nextLevel();
        }
        $bIsFirst = true;
        foreach ($aValues as $mValue) {
            if ($bIsFirst) {
                $bIsFirst = false;
            } else {
                $sResult .= $sSeparator;
            }
            if ($mValue instanceof Renderable) {
                $sResult .= $mValue->render($oFormat);
            } else {
                $sResult .= $mValue;
            }
        }
        return $sResult;
    }

    public function removeLastSemicolon(string $sString): string
    {
        if ($this->oFormat->get('SemicolonAfterLastRule')) {
            return $sString;
        }
        $sString = explode(';', $sString);
        if (count($sString) < 2) {
            return $sString[0];
        }
        $sLast = array_pop($sString);
        $sNextToLast = array_pop($sString);
        array_push($sString, $sNextToLast . $sLast);
        return implode(';', $sString);
    }

    /**
     *
     * @param array<Commentable> $aComments
     */
    public function comments(Commentable $oCommentable): string
    {
        if (!$this->oFormat->bRenderComments) {
            return '';
        }

        $sResult = '';
        $aComments = $oCommentable->getComments();
        $iLastCommentIndex = count($aComments) - 1;

        foreach ($aComments as $i => $oComment) {
            $sResult .= $oComment->render($this->oFormat);
            $sResult .= $i === $iLastCommentIndex ? $this->spaceAfterBlocks() : $this->spaceBetweenBlocks();
        }
        return $sResult;
    }

    private function prepareSpace(string $sSpaceString): string
    {
        return str_replace("\n", "\n" . $this->indent(), $sSpaceString);
    }

    private function indent(): string
    {
        return str_repeat($this->oFormat->sIndentation, $this->oFormat->level());
    }
}
