<?php

namespace Sabberworm\CSS;

/**
 * Parser settings class.
 *
 * Configure parser behaviour here.
 */
class Settings
{
    /**
     * Multi-byte string support.
     * If true (mbstring extension must be enabled), will use (slower) `mb_strlen`, `mb_convert_case`, `mb_substr`
     * and `mb_strpos` functions. Otherwise, the normal (ASCII-Only) functions will be used.
     *
     * @var bool
     */
    public $bMultibyteSupport;

    /**
     * The default charset for the CSS if no `@charset` rule is found. Defaults to utf-8.
     *
     * @var string
     */
    public $sDefaultCharset = 'utf-8';

    /**
     * Lenient parsing. When used (which is true by default), the parser will not choke
     * on unexpected tokens but simply ignore them.
     *
     * @var bool
     */
    public $bLenientParsing = true;

    private function __construct()
    {
        $this->bMultibyteSupport = extension_loaded('mbstring');
    }

    /**
     * @return self new instance
     */
    public static function create(): \Sabberworm\CSS\Settings
    {
        return new Settings();
    }

    /**
     * @return self fluent interface
     */
    public function withMultibyteSupport(bool $bMultibyteSupport = true): self
    {
        $this->bMultibyteSupport = $bMultibyteSupport;
        return $this;
    }

    /**
     * @return self fluent interface
     */
    public function withDefaultCharset(string $sDefaultCharset): self
    {
        $this->sDefaultCharset = $sDefaultCharset;
        return $this;
    }

    /**
     * @return self fluent interface
     */
    public function withLenientParsing(bool $bLenientParsing = true): self
    {
        $this->bLenientParsing = $bLenientParsing;
        return $this;
    }

    /**
     * @return self fluent interface
     */
    public function beStrict(): \Sabberworm\CSS\Settings
    {
        return $this->withLenientParsing(false);
    }
}
