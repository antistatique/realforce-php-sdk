<?php

namespace Antistatique\Realforce\Resource;

use Antistatique\Realforce\RealforceClient;

/**
 * Realforce base API Resource class.
 */
interface ResourceInterface
{
    /**
     * Get the API client provider.
     *
     * @return RealforceClient the Realforce base API instance
     */
    public function getClient(): RealforceClient;
}
