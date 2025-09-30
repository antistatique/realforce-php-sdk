<?php

namespace Antistatique\Realforce\Request;

/**
 * Request object for Locations labels.
 *
 * @see \Antistatique\Realforce\Resource\PublicProperties
 */
final class LocationsRequest
{
    /**
     * Retrieve a list of countries.
     *
     * @var bool
     */
    private bool $isCountry = false;

    /**
     * Retrieve a list of cantons.
     *
     * @var bool
     */
    private bool $isCanton = false;

    /**
     * Retrieve a list of districts.
     *
     * @var bool
     */
    private bool $isDistrict = false;

    /**
     * Retrieve a list of zones.
     *
     * @var bool
     */
    private bool $isZone = false;

    /**
     * Retrieve a list of quarter.
     *
     * @var bool
     */
    private bool $isQuarter = false;

    /**
     * Retrieve a list of city.
     *
     * @var bool
     */
    private bool $isCity = false;

    /**
     * ID of a country to filter on.
     *
     * @var int|null
     */
    private ?int $countryId = null;

    /**
     * ID of a canton to filter on.
     *
     * @var int|null
     */
    private ?int $cantonId = null;

    /**
     * ID of a district to filter on.
     *
     * @var int|null
     */
    private ?int $districtId = null;

    /**
     * ID of a zone to filter on.
     *
     * @var int|null
     */
    private ?int $zoneId = null;

    /**
     * ID of a quarter to filter on.
     *
     * @var int|null
     */
    private ?int $quarterId = null;

    /**
     * ID of a city to filter on.
     *
     * @var int|null
     */
    private ?int $cityId = null;

    /**
     * Content languages in lower case (fr, en, it, de).
     *
     * Multiple languages can be retrieved using the "pipe" (|) separator.
     *
     * @var string
     */
    private string $lang;

    /**
     * Set to retrieve a list of countries.
     */
    public function isCountry(): self
    {
        $this->isCountry = true;

        return $this;
    }

    /**
     * Set to retrieve a list of cantons.
     */
    public function isCanton(): self
    {
        $this->isCanton = true;

        return $this;
    }

    /**
     * Set to retrieve a list of districts.
     */
    public function isDistrict(): self
    {
        $this->isDistrict = true;

        return $this;
    }

    /**
     * Set to retrieve a list of zones.
     */
    public function isZone(): self
    {
        $this->isZone = true;

        return $this;
    }

    /**
     * Set to retrieve a list of quarter.
     */
    public function isQuarter(): self
    {
        $this->isQuarter = true;

        return $this;
    }

    /**
     * Set to retrieve a list of city.
     */
    public function isCity(): self
    {
        $this->isCity = true;

        return $this;
    }

    /**
     * Set country ID to filter on.
     */
    public function countryId(int $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Set canton ID to filter on.
     */
    public function cantonId(int $cantonId): self
    {
        $this->cantonId = $cantonId;

        return $this;
    }

    /**
     * Set district ID to filter on.
     */
    public function districtId(int $districtId): self
    {
        $this->districtId = $districtId;

        return $this;
    }

    /**
     * Set zone ID to filter on.
     */
    public function zoneId(int $zoneId): self
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    /**
     * Set quarter ID to filter on.
     */
    public function quarterId(int $quarterId): self
    {
        $this->quarterId = $quarterId;

        return $this;
    }

    /**
     * Set city ID to filter on.
     */
    public function cityId(int $cityID): self
    {
        $this->cityId = $cityID;

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
     * Convert the request to an array for API consumption.
     */
    public function toArray(): array
    {
        $params = [];

        $params['lang'] = $this->lang;

        if ($this->isCountry) {
            $params['is_country'] = 1;
        }

        if ($this->isCanton) {
            $params['is_canton'] = 1;
        }

        if ($this->isDistrict) {
            $params['is_district'] = 1;
        }

        if ($this->isZone) {
            $params['is_zone'] = 1;
        }

        if ($this->isQuarter) {
            $params['is_quarter'] = 1;
        }

        if ($this->isCity) {
            $params['is_city'] = 1;
        }

        if (null !== $this->countryId) {
            $params['country_id'] = $this->countryId;
        }

        if (null !== $this->cantonId) {
            $params['canton_id'] = $this->cantonId;
        }

        if (null !== $this->districtId) {
            $params['district_id'] = $this->districtId;
        }

        if (null !== $this->zoneId) {
            $params['zone_id'] = $this->zoneId;
        }

        if (null !== $this->quarterId) {
            $params['quarter_id'] = $this->quarterId;
        }

        if (null !== $this->cityId) {
            $params['city_id'] = $this->cityId;
        }

        return $params;
    }
}
