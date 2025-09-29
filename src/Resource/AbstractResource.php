<?php

namespace Antistatique\Realforce\Resource;

use Antistatique\Realforce\RealforceClient;

/**
 * Realforce base API class.
 */
abstract class AbstractResource implements ResourceInterface
{
    /**
     * The Realforce client provider.
     *
     * @var RealforceClient
     */
    private RealforceClient $realforceClient;

    /**
     * Construct a new AbstractApi object.
     *
     * @param RealforceClient $realforceClient the Realforce base API class
     */
    public function __construct(RealforceClient $realforceClient)
    {
        $this->setClient($realforceClient);
    }

    /**
     * Set the API client provider.
     *
     * @param RealforceClient $realforceClient the Realforce base API instance
     *
     * @return $this
     */
    public function setClient(RealforceClient $realforceClient): self
    {
        $this->realforceClient = $realforceClient;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClient(): RealforceClient
    {
        return $this->realforceClient;
    }
}
