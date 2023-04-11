<?php

namespace NextBox\Neos\UrlShortener;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Neos\Service\PublishingService;
use NextBox\Neos\UrlShortener\Services\BackendServiceInterface;

/**
 * Class Package
 */
class Package extends BasePackage
{
    /**
     * @param Bootstrap $bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        /**
         * Update the short identifier or create a new entry if a node was published
         */
        $dispatcher->connect(
            PublishingService::class,
            'nodePublished',
            BackendServiceInterface::class,
            'updateNode'
        );
    }
}
