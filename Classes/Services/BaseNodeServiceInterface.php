<?php

namespace NextBox\Neos\UrlShortener\Services;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Model\NodeInterface;

/**
 * Backend Service Interface
 */
interface BaseNodeServiceInterface
{
    /**
     * Get the base node
     *
     * @param string $shortType
     * @param NodeData $targetNode
     * @return NodeInterface
     */
    public function getBaseNode(string $shortType, NodeData $targetNode): NodeInterface;
}
