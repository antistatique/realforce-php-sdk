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
     * @var string|null
     */
    private ?string $lang = null;

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
     *
     * @throws \LogicException when the mandatory "lang" parameter is missing
     */
    public function toArray(): array
    {
        if (null === $this->lang) {
            throw new \LogicException('The "lang" parameter is mandatory, call lang() before toArray().');
        }

        $params = [];

        $params['lang'] = $this->lang;

        return $params;
    }
}
