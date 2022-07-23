<?php

namespace Sabberworm\CSS\Value;

use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Parsing\ParserState;
use Sabberworm\CSS\Parsing\UnexpectedEOFException;
use Sabberworm\CSS\Parsing\UnexpectedTokenException;

class Color extends CSSFunction
{
    /**
     * @param array<int, RuleValueList|CSSFunction|CSSString|LineName|Size|URL|string> $aColor
     * @param int $iLineNo
     */
    public function __construct(array $aColor, $iLineNo = 0)
    {
        parent::__construct(implode('', array_keys($aColor)), $aColor, ',', $iLineNo);
    }

    /**
     * @return Color|CSSFunction
     *
     * @throws UnexpectedEOFException
     * @throws UnexpectedTokenException
     */
    public static function parse(ParserState $oParserState): CSSFunction
    {
        $aColor = [];
        if ($oParserState->comes('#')) {
            $oParserState->consume('#');
            $sValue = $oParserState->parseIdentifier(false);
            if ($oParserState->strlen($sValue) === 3) {
                $sValue = $sValue[0] . $sValue[0] . $sValue[1] . $sValue[1] . $sValue[2] . $sValue[2];
            } elseif ($oParserState->strlen($sValue) === 4) {
                $sValue = $sValue[0] . $sValue[0] . $sValue[1] . $sValue[1] . $sValue[2] . $sValue[2] . $sValue[3]
                    . $sValue[3];
            }

            if ($oParserState->strlen($sValue) === 8) {
                $aColor = [
                    'r' => new Size(intval($sValue[0] . $sValue[1], 16), null, true, $oParserState->currentLine()),
                    'g' => new Size(intval($sValue[2] . $sValue[3], 16), null, true, $oParserState->currentLine()),
                    'b' => new Size(intval($sValue[4] . $sValue[5], 16), null, true, $oParserState->currentLine()),
                    'a' => new Size(
                        round(self::mapRange(intval($sValue[6] . $sValue[7], 16), 0, 255, 0, 1), 2),
                        null,
                        true,
                        $oParserState->currentLine()
                    ),
                ];
            } else {
                $aColor = [
                    'r' => new Size(intval($sValue[0] . $sValue[1], 16), null, true, $oParserState->currentLine()),
                    'g' => new Size(intval($sValue[2] . $sValue[3], 16), null, true, $oParserState->currentLine()),
                    'b' => new Size(intval($sValue[4] . $sValue[5], 16), null, true, $oParserState->currentLine()),
                ];
            }
        } else {
            $sColorMode = $oParserState->parseIdentifier(true);
            $oParserState->consumeWhiteSpace();
            $oParserState->consume('(');

            $bContainsVar = false;
            $iLength = $oParserState->strlen($sColorMode);
            for ($i = 0; $i < $iLength; ++$i) {
                $oParserState->consumeWhiteSpace();
                if ($oParserState->comes('var')) {
                    $aColor[$sColorMode[$i]] = CSSFunction::parseIdentifierOrFunction($oParserState);
                    $bContainsVar = true;
                } else {
                    $aColor[$sColorMode[$i]] = Size::parse($oParserState, true);
                }

                if ($bContainsVar && $oParserState->comes(')')) {
                    // With a var argument the function can have fewer arguments
                    break;
                }

                $oParserState->consumeWhiteSpace();
                if ($i < ($iLength - 1)) {
                    $oParserState->consume(',');
                }
            }
            $oParserState->consume(')');

            if ($bContainsVar) {
                return new CSSFunction($sColorMode, array_values($aColor), ',', $oParserState->currentLine());
            }
        }
        return new Color($aColor, $oParserState->currentLine());
    }

    private static function mapRange(float $fVal, float $fFromMin, float $fFromMax, float $fToMin, float $fToMax): float
    {
        $fFromRange = $fFromMax - $fFromMin;
        $fToRange = $fToMax - $fToMin;
        $fMultiplier = $fToRange / $fFromRange;
        $fNewVal = $fVal - $fFromMin;
        $fNewVal *= $fMultiplier;
        return $fNewVal + $fToMin;
    }

    /**
     * @return array<int, RuleValueList|CSSFunction|CSSString|LineName|Size|URL|string>
     */
    public function getColor(): array
    {
        return $this->aComponents;
    }

    /**
     * @param array<int, RuleValueList|CSSFunction|CSSString|LineName|Size|URL|string> $aColor
     */
    public function setColor(array $aColor): void
    {
        $this->setName(implode('', array_keys($aColor)));
        $this->aComponents = $aColor;
    }

    public function getColorDescription(): string
    {
        return $this->getName();
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
        // Shorthand RGB color values
        if ($oOutputFormat->getRGBHashNotation() && implode('', array_keys($this->aComponents)) === 'rgb') {
            $sResult = sprintf(
                '%02x%02x%02x',
                $this->aComponents['r']->getSize(),
                $this->aComponents['g']->getSize(),
                $this->aComponents['b']->getSize()
            );
            return '#' . (($sResult[0] == $sResult[1]) && ($sResult[2] == $sResult[3]) && ($sResult[4] == $sResult[5])
                    ? "$sResult[0]$sResult[2]$sResult[4]" : $sResult);
        }
        return parent::render($oOutputFormat);
    }
}
