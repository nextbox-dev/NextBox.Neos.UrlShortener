<?php

namespace NextBox\Neos\UrlShortener\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\PersistentResource;

/**
 * @Flow\Entity
 */
class UrlShortener
{
    /**
     * @var string
     */
    protected string $shortIdentifier;

    /**
     * @ORM\OneToOne(cascade={"all"})
     * @var PersistentResource|null
     */
    protected ?PersistentResource $resource = null;

    /**
     * @var string
     */
    protected string $shortType = 'default';

    /**
     * @ORM\ManyToOne
     * @var NodeData
     */
    protected NodeData $node;

    /**
     * Getter for shortIdentifier
     *
     * @return string
     */
    public function getShortIdentifier(): string
    {
        return $this->shortIdentifier;
    }

    /**
     * Setter for shortIdentifier
     *
     * @param string $shortIdentifier
     * @return UrlShortener
     */
    public function setShortIdentifier(string $shortIdentifier): UrlShortener
    {
        $this->shortIdentifier = $shortIdentifier;

        return $this;
    }

    /**
     * Getter for resource
     *
     * @return PersistentResource|null
     */
    public function getResource(): ?PersistentResource
    {
        return $this->resource;
    }

    /**
     * Setter for resource
     *
     * @param PersistentResource|null $resource
     * @return UrlShortener
     */
    public function setResource(?PersistentResource $resource): UrlShortener
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Getter for shortType
     *
     * @return string
     */
    public function getShortType(): string
    {
        return $this->shortType;
    }

    /**
     * Setter for shortType
     *
     * @param string $shortType
     * @return UrlShortener
     */
    public function setShortType(string $shortType): UrlShortener
    {
        $this->shortType = $shortType;

        return $this;
    }

    /**
     * Getter for node
     *
     * @return NodeData
     */
    public function getNode(): NodeData
    {
        return $this->node;
    }

    /**
     * Setter for node
     *
     * @param NodeData $node
     * @return UrlShortener
     */
    public function setNode(NodeData $node): UrlShortener
    {
        $this->node = $node;

        return $this;
    }
}
