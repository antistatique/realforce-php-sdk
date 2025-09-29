<?php

namespace Antistatique\Realforce\Request;

/**
 * Request object for Properties list API endpoint.
 *
 * @see \Antistatique\Realforce\Resource\PublicProperties
 */
final class PropertiesListRequest
{
    /**
     * Number of records to retrieve per page (max 100).
     *
     * @var int
     */
    private int $perPage = 100;

    /**
     * Index of the page to retrieve.
     *
     * @var int
     */
    private int $page = 0;

    /**
     * Content languages in lower case (fr, en, it, de).
     *
     * Multiple languages can be retrieved using the "pipe" (|) separator.
     *
     * @var string|null
     */
    private ?string $lang = null;

    /**
     * Filter only the properties after the given date.
     *
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $updatedAfter = null;

    /**
     * Filter only the properties before the date give in parameter.
     *
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $updatedBefore = null;

    /**
     * Set the number of records to retrieve per page (max 100).
     */
    public function perPage(int $perPage): self
    {
        if ($perPage > 100) {
            throw new \InvalidArgumentException('per_page cannot exceed 100');
        }

        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Set the index of the page to retrieve.
     */
    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set content languages (fr, en, it, de).
     */
    public function lang(array $lang): self
    {
        $this->lang = implode('|', $lang);

        return $this;
    }

    /**
     * Filter only properties updated after the given date.
     */
    public function updatedAfter(\DateTimeInterface $after): self
    {
        $this->updatedAfter = $after;

        return $this;
    }

    /**
     * Filter only properties updated before the given date.
     */
    public function updatedBefore(\DateTimeInterface $before): self
    {
        $this->updatedBefore = $before;

        return $this;
    }

    /**
     * Convert the request to an array for API consumption.
     */
    public function toArray(): array
    {
        $params = [];

        $params['per_page'] = $this->perPage;
        $params['page'] = $this->page;

        if (null !== $this->lang) {
            $params['lang'] = $this->lang;
        }

        if (null !== $this->updatedAfter) {
            $params['update_date_min'] = $this->updatedAfter->format('Y-m-d');
        }

        if (null !== $this->updatedBefore) {
            $params['update_date_max'] = $this->updatedBefore->format('Y-m-d');
        }

        return $params;
    }
}
