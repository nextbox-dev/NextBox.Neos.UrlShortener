<?php

namespace NextBox\Neos\UrlShortener\Services;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Controller\CreateContentContextTrait;
use NextBox\Neos\UrlShortener\Domain\Model\UrlShortener;
use NextBox\Neos\UrlShortener\Domain\Repository\UrlShortenerRepository;

/**
 * @Flow\Scope("singleton")
 */
class BackendService
{
    use CreateContentContextTrait;

    /**
     * @Flow\Inject
     * @var UrlShortenerRepository
     */
    protected $urlShortenerRepository;

    /**
     * @Flow\InjectConfiguration(path="shortTypes")
     * @var array
     */
    protected array $typeSettings;

    /**
     * @Flow\Inject()
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Loop to update the short identifier or create a new entry
     *
     * @param NodeInterface $node
     * @return void
     */
    public function updateNode(NodeInterface $node): void
    {
        foreach ($this->typeSettings as $shortType => $setting) {
            $nodeTypeName = $setting['nodeType'];
            $propertyName = $setting['property'];

            $this->handle($node, $shortType, $nodeTypeName, $propertyName);
        }
    }

    /**
     * Update the short identifier or create a new entry
     *
     * @param NodeInterface $node
     * @param string $shortType
     * @param string $nodeTypeName
     * @param string $propertyName
     * @return UrlShortener|null
     */
    protected function handle(NodeInterface $node, string $shortType, string $nodeTypeName, string $propertyName): ?UrlShortener
    {
        $propertyValue = $node->hasProperty($propertyName) ? $node->getProperty($propertyName) : null;

        if ($node->getNodeType()->isOfType($nodeTypeName) && $propertyValue) {
            $urlShortener = $this->urlShortenerRepository->findOneByNodeDataAndShortType($node->getNodeData(), $shortType);
            $newObject = false;

            if (!$urlShortener instanceof UrlShortener) {
                $urlShortener = new UrlShortener();
                $urlShortener->setNode($node->getNodeData());
                $urlShortener->setShortType($shortType);
                $newObject = true;
            }

            $urlShortener->setShortIdentifier($propertyValue);
            $urlShortener->setResource(null);

            if ($newObject) {
                $this->urlShortenerRepository->add($urlShortener);
            } else {
                $this->urlShortenerRepository->update($urlShortener);
            }

            return $urlShortener;
        }

        return null;
    }
}
