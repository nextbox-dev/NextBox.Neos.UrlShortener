<?php

namespace NextBox\Neos\UrlShortener\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use NextBox\Neos\UrlShortener\Domain\Model\UrlShortener;
use NextBox\Neos\UrlShortener\Domain\Repository\UrlShortenerRepository;
use NextBox\Neos\UrlShortener\Services\RedirectService;

class UrlShortenerCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var RedirectService
     */
    protected $redirectService;

    /**
     * @Flow\Inject
     * @var UrlShortenerRepository
     */
    protected $urlShortenerRepository;

    /**
     * Initialize all nodes of a short type
     *
     * @param string $shortType name of the short type
     * @param bool $forceRecreation regenerate if there is already a short type existing
     * @param int $offset offset for nodes
     * @param int $limit limit to read nodes
     * @return void
     */
    public function initCommand(string $shortType, bool $forceRecreation = false, int $offset = 0, int $limit = 999999): void
    {
        $nodes = $this->redirectService->getNodesByShortType($shortType, $offset, $limit);
        $propertyName = $this->redirectService->getPropertyNameOfType($shortType);

        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                $shortIdentifier = $node->hasProperty($propertyName) ? $node->getProperty($propertyName) : null;
                if (!$shortIdentifier) {
                    continue;
                }

                $urlShortener = $this->urlShortenerRepository->findOneByNodeAndShortType($node, $shortType);

                if ($urlShortener instanceof UrlShortener && !$forceRecreation) {
                    continue;
                }

                if (!$urlShortener instanceof UrlShortener) {
                    $urlShortener = new UrlShortener();
                    $urlShortener->setNode($node->getNodeData());
                    $urlShortener->setShortType($shortType);
                    $this->urlShortenerRepository->add($urlShortener);
                }

                $urlShortener->setShortIdentifier($shortIdentifier);
                $this->urlShortenerRepository->update($urlShortener);
            }
        }
    }
}
