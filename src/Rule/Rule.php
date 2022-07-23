<?php

namespace Sabberworm\CSS\Rule;

use Sabberworm\CSS\Comment\Comment;
use Sabberworm\CSS\Comment\Commentable;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\UnexpectedEOFException;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;
use Sabberworm\CSS\Renderable;
use Sabberworm\CSS\Value\RuleValueList;
use Sabberworm\CSS\Value\Value;

/**
 * RuleSets contains Rule objects which always have a key and a value.
 * In CSS, Rules are expressed as follows: “key: value[0][0] value[0][1], value[1][0] value[1][1];”
 */
class Rule implements Renderable, Commentable
{
    private string $sRule;

    /**
     * @var mixed previously as RuleValueList|string|null
     */
    private $mValue = null;

    private bool $bIsImportant;

    /**
     * @var mixed[]|int[]
     */
    private $aIeHack;

    protected int $iLineNo;

    protected int $iColNo;

    /**
     * @var array<array-key, Comment>
     */
    protected $aComments;

    /**
     * @param string $sRule
     * @param int $iLineNo
     * @param int $iColNo
     */
    public function __construct(string $sRule, int $iLineNo = 0, int $iColNo = 0)
    {
        $this->sRule = $sRule;
        $this->mValue = null;
        $this->bIsImportant = false;
        $this->aIeHack = [];
        $this->iLineNo = $iLineNo;
        $this->iColNo = $iColNo;
        $this->aComments = [];
    }

    /**
     *
     * @throws UnexpectedEOFException
     * @throws UnexpectedTokenException
     */
    public static function parse(ParserState $oParserState): \Sabberworm\CSS\Rule\Rule
    {
        $aComments = $oParserState->consumeWhiteSpace();
        $oRule = new Rule(
            $oParserState->parseIdentifier(!$oParserState->comes("--")),
            $oParserState->currentLine(),
            $oParserState->currentColumn()
        );
        $oRule->setComments($aComments);
        $oRule->addComments($oParserState->consumeWhiteSpace());
        $oParserState->consume(':');
        $oValue = Value::parseValue($oParserState, self::listDelimiterForRule($oRule->getRule()));
        $oRule->setValue($oValue);
        if ($oParserState->getSettings()->bLenientParsing) {
            while ($oParserState->comes('\\')) {
                $oParserState->consume('\\');
                $oRule->addIeHack($oParserState->consume(1));
                $oParserState->consumeWhiteSpace();
            }
        }
        $oParserState->consumeWhiteSpace();
        if ($oParserState->comes('!')) {
            $oParserState->consume('!');
            $oParserState->consumeWhiteSpace();
            $oParserState->consume('important');
            $oRule->setIsImportant(true);
        }
        $oParserState->consumeWhiteSpace();
        while ($oParserState->comes(';')) {
            $oParserState->consume(';');
        }
        $oParserState->consumeWhiteSpace();

        return $oRule;
    }

    /**
     * @return array<int, string>
     */
    private static function listDelimiterForRule(string $sRule): array
    {
        if (preg_match('/^font($|-)/', $sRule)) {
            return [',', '/', ' '];
        }
        return [',', ' ', '/'];
    }

    public function getLineNo(): int
    {
        return $this->iLineNo;
    }

    public function getColNo(): int
    {
        return $this->iColNo;
    }

    public function setPosition(int $iLine, int $iColumn): void
    {
        $this->iColNo = $iColumn;
        $this->iLineNo = $iLine;
    }

    public function setRule(string $sRule): void
    {
        $this->sRule = $sRule;
    }

    public function getRule(): string
    {
        return $this->sRule;
    }

    /**
     * @return mixed previously as RuleValueList|string|null
     */
    public function getValue()
    {
        return $this->mValue;
    }

    /**
     * @param RuleValueList|string|null $mValue
     */
    public function setValue($mValue): void
    {
        $this->mValue = $mValue;
    }

    /**
     * @param array<array-key, array<array-key, RuleValueList>> $aSpaceSeparatedValues
     *
     *
     * @deprecated will be removed in version 9.0
     *             Old-Style 2-dimensional array given. Retained for (some) backwards-compatibility.
     *             Use `setValue()` instead and wrap the value inside a RuleValueList if necessary.
     */
    public function setValues(array $aSpaceSeparatedValues): ?RuleValueList
    {
        $oSpaceSeparatedList = null;
        if (count($aSpaceSeparatedValues) > 1) {
            $oSpaceSeparatedList = new RuleValueList(' ', $this->iLineNo);
        }
        foreach ($aSpaceSeparatedValues as $aCommaSeparatedValues) {
            $oCommaSeparatedList = null;
            if (count($aCommaSeparatedValues) > 1) {
                $oCommaSeparatedList = new RuleValueList(',', $this->iLineNo);
            }
            foreach ($aCommaSeparatedValues as $mValue) {
                if (!$oSpaceSeparatedList && !$oCommaSeparatedList) {
                    $this->mValue = $mValue;
                    return $mValue;
                }
                if ($oCommaSeparatedList) {
                    $oCommaSeparatedList->addListComponent($mValue);
                } else {
                    $oSpaceSeparatedList->addListComponent($mValue);
                }
            }
            if (!$oSpaceSeparatedList) {
                $this->mValue = $oCommaSeparatedList;
                return $oCommaSeparatedList;
            } else {
                $oSpaceSeparatedList->addListComponent($oCommaSeparatedList);
            }
        }
        $this->mValue = $oSpaceSeparatedList;
        return $oSpaceSeparatedList;
    }

    /**
     * @return array<int, array<int, RuleValueList|null>>
     *
     * @deprecated will be removed in version 9.0
     *             Old-Style 2-dimensional array returned. Retained for (some) backwards-compatibility.
     *             Use `getValue()` instead and check for the existence of a (nested set of) ValueList object(s).
     */
    public function getValues(): array
    {
        if (!$this->mValue instanceof RuleValueList) {
            return [[$this->mValue]];
        }
        if ($this->mValue->getListSeparator() === ',') {
            return [$this->mValue->getListComponents()];
        }
        $aResult = [];
        foreach ($this->mValue->getListComponents() as $mValue) {
            if (!$mValue instanceof RuleValueList || $mValue->getListSeparator() !== ',') {
                $aResult[] = [$mValue];
                continue;
            }
            if ($this->mValue->getListSeparator() === ' ' || count($aResult) === 0) {
                $aResult[] = [];
            }
            foreach ($mValue->getListComponents() as $mValue) {
                $aResult[count($aResult) - 1][] = $mValue;
            }
        }
        return $aResult;
    }

    /**
     * Adds a value to the existing value. Value will be appended if a `RuleValueList` exists of the given type.
     * Otherwise, the existing value will be wrapped by one.
     *
     * @param mixed $mValue previously as RuleValueList|array<int, RuleValueList>
     * @param string $sType value type
     * @return void
     *
     */
    public function addValue($mValue, string $sType = ' '): void
    {
        if (!is_array($mValue)) {
            $mValue = [$mValue];
        }
        if (!$this->mValue instanceof RuleValueList || $this->mValue->getListSeparator() !== $sType) {
            $mCurrentValue = $this->mValue;
            $this->mValue = new RuleValueList($sType, $this->iLineNo);
            if ($mCurrentValue) {
                $this->mValue->addListComponent($mCurrentValue);
            }
        }
        foreach ($mValue as $mValueItem) {
            $this->mValue->addListComponent($mValueItem);
        }
    }

    public function addIeHack(int $iModifier): void
    {
        $this->aIeHack[] = $iModifier;
    }

    /**
     * @param array<int, int> $aModifiers
     */
    public function setIeHack(array $aModifiers): void
    {
        $this->aIeHack = $aModifiers;
    }

    /**
     * @return array<int, int>
     */
    public function getIeHack(): array
    {
        return $this->aIeHack;
    }

    public function setIsImportant(bool $bIsImportant): void
    {
        $this->bIsImportant = $bIsImportant;
    }

    public function getIsImportant(): bool
    {
        return $this->bIsImportant;
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
        $sResult = "{$oOutputFormat->comments($this)}{$this->sRule}:{$oOutputFormat->spaceAfterRuleName()}";
        if ($this->mValue instanceof Value) { // Can also be a ValueList
            $sResult .= $this->mValue->render($oOutputFormat);
        } else {
            $sResult .= $this->mValue;
        }
        if (!empty($this->aIeHack)) {
            $sResult .= ' \\' . implode('\\', $this->aIeHack);
        }
        if ($this->bIsImportant) {
            $sResult .= ' !important';
        }
        $sResult .= ';';
        return $sResult;
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
