<?php

namespace Antistatique\Realforce\Request;

/**
 * Request object for internationalization/localization parameters.
 *
 * @see \Antistatique\Realforce\Resource\PublicProperties
 */
final class I18nRequest
{
    /**
     * Content languages in lower case (fr, en, it, de).
     *
     * Multiple languages can be retrieved using the "pipe" (|) separator.
     *
     * @var string
     */
    private string $lang;

    /**
     * Set content languages (fr, en, it, de).
     */
    public function lang(array $lang): self
    {
        $this->lang = implode('|', $lang);

        return $this;
    }

    /**
     * Convert the request to an array for API consumption.
     */
    public function toArray(): array
    {
        $params = [];

        $params['lang'] = $this->lang;

        return $params;
    }
}
