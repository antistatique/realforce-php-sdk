<?php

namespace Antistatique\Realforce\Resource;

use Antistatique\Realforce\RealforceClient;
use Antistatique\Realforce\Request\PropertiesListRequest;

/**
 * The Realforce Properties API class.
 *
 * @see https://github.com/realforce/documentation/blob/master/api-public/endpoints/properties-list.md
 */
final class PublicProperties extends AbstractResource
{
    /**
     * The Realforce Properties base API URL.
     */
    public const BASE_URL = 'https://listings.realforce.ch/api/v1/';

    /**
     * Fetch a list of published properties' public data.
     *
     * @param PropertiesListRequest $request the request parameters
     * @param int                   $timeout timeout limit for request in seconds
     *
     * @return array|bool a decoded array of result or a boolean on unattended response
     *
     * @throws \Exception
     */
    public function list(PropertiesListRequest $request, int $timeout = RealforceClient::TIMEOUT)
    {
        return $this->getClient()->makeRequest('get', self::BASE_URL.'/get-full-listings', $request->toArray(), $timeout);
    }
}
