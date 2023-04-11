<?php

namespace NextBox\Neos\UrlShortener\Services;

use Neos\ContentRepository\Domain\Model\NodeInterface;

/**
 * Backend Service Interface
 */
interface BackendServiceInterface
{
    /**
     * Update the short identifier or create a new entry
     *
     * @param NodeInterface $node
     * @return void
     */
    public function updateNode(NodeInterface $node): void;
}
