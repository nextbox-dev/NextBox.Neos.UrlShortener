<?php

namespace NextBox\Neos\UrlShortener\Domain\Repository;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use NextBox\Neos\UrlShortener\Domain\Model\UrlShortener;

/**
 * @Flow\Scope("singleton")
 */
class UrlShortenerRepository extends Repository
{
    /**
     * Get one url shortener by short identifier and short type
     *
     * @param string $shortIdentifier
     * @param string $shortType
     * @return UrlShortener|null
     */
    public function findOneByShortIdentifierAndShortType(string $shortIdentifier, string $shortType = 'default'): ?UrlShortener
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('shortIdentifier', $shortIdentifier),
                $query->equals('shortType', $shortType)
            )
        );

        return $query->execute()->getFirst();
    }

    /**
     * Get one url shortener by node data and short type
     *
     * @param NodeData $nodeData
     * @param string $shortType
     * @return UrlShortener|null
     */
    public function findOneByNodeDataAndShortType(NodeData $nodeData, string $shortType = 'default'): ?UrlShortener
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('node', $nodeData),
                $query->equals('shortType', $shortType)
            )
        );

        return $query->execute()->getFirst();
    }

    /**
     * Get one url shortener by node and short type
     *
     * @param NodeInterface $node
     * @param string $shortType
     * @return UrlShortener|null
     */
    public function findOneByNodeAndShortType(NodeInterface $node, string $shortType = 'default'): ?UrlShortener
    {
        return $this->findOneByNodeDataAndShortType($node->getNodeData(), $shortType);
    }
}
