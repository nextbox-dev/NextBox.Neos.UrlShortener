<?php

namespace NextBox\Neos\UrlShortener\Domain\Repository;

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
}
