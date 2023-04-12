<?php

namespace NextBox\Neos\UrlShortener\Services;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Controller\CreateContentContextTrait;

/**
 * Base Node Service to get the base node on which the uri gets generated
 *
 * @Flow\Scope("singleton")
 */
class BaseNodeService implements BaseNodeServiceInterface
{
    use CreateContentContextTrait;

    /**
     * @Flow\InjectConfiguration(path="shortTypes", package="NextBox.Neos.UrlShortener")
     * @var array
     */
    protected array $typeSettings;


    /**
     * Get the base node
     *
     * @param string $shortType
     * @param NodeData|null $targetNode
     * @return NodeInterface
     */
    public function getBaseNode(string $shortType, ?NodeData $targetNode = null): NodeInterface
    {
        $contentContext = $this->createContentContext('live');
        $baseNode = $contentContext->getCurrentSiteNode();

        if (key_exists('rootNodePath', $this->typeSettings[$shortType]) && $this->typeSettings[$shortType]['rootNodePath']) {
            $baseNode = $contentContext->getNode($this->typeSettings[$shortType]['rootNodePath']);
        }

        return $baseNode;
    }
}
