<?php

namespace Antistatique\Realforce\Resource;

use Antistatique\Realforce\RealforceClient;
use Antistatique\Realforce\Request\I18nRequest;
use Antistatique\Realforce\Request\LocationsRequest;

/**
 * The Realforce Labels API class.
 *
 * @see https://github.com/realforce/documentation/blob/master/api-public/endpoints/labels-locations.md
 * @see https://github.com/realforce/documentation/blob/master/api-public/endpoints/labels-categories.md
 * @see https://github.com/realforce/documentation/blob/master/api-public/endpoints/labels-amenities.md
 */
final class PublicLabels extends AbstractResource
{
    /**
     * The Realforce Labels base API URL.
     */
    public const BASE_URL = 'https://labels.realforce.ch/api/v1/';

    /**
     * Fetch "amenities" labels linked to the public data you retrieve from the public API endpoints.
     *
     * @param I18nRequest $request the request parameters
     * @param int         $timeout timeout limit for request in seconds
     *
     * @return array|bool a decoded array of result or a boolean on unattended response
     *
     * @throws \Exception
     */
    public function amenities(I18nRequest $request, int $timeout = RealforceClient::TIMEOUT)
    {
        return $this->getClient()->makeRequest('get', self::BASE_URL.'/get-amenities-labels', $request->toArray(), $timeout);
    }

    /**
     * Fetch "categories" labels linked to the public data you retrieve from the public API endpoints.
     *
     * @param I18nRequest $request the request parameters
     * @param int         $timeout timeout limit for request in seconds
     *
     * @return array|bool a decoded array of result or a boolean on unattended response
     *
     * @throws \Exception
     */
    public function categories(I18nRequest $request, int $timeout = RealforceClient::TIMEOUT)
    {
        return $this->getClient()->makeRequest('get', self::BASE_URL.'/get-categories-labels', $request->toArray(), $timeout);
    }

    /**
     * Fetch "locations" labels linked to the public data you retrieve from the public API endpoints.
     *
     * @param LocationsRequest $request the request parameters
     * @param int              $timeout timeout limit for request in seconds
     *
     * @return array|bool a decoded array of result or a boolean on unattended response
     *
     * @throws \Exception
     */
    public function locations(LocationsRequest $request, int $timeout = RealforceClient::TIMEOUT)
    {
        return $this->getClient()->makeRequest('get', self::BASE_URL.'/get-locations', $request->toArray(), $timeout);
    }
}
