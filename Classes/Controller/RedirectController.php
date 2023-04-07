<?php
namespace NextBox\Neos\UrlShortener\Controller;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Exception\PageNotFoundException;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Neos\Service\LinkingService;
use NextBox\Neos\UrlShortener\Services\RedirectService;

class RedirectController extends ActionController
{

    /**
     * @Flow\Inject
     * @var LinkingService
     */
    protected $linkingService;

    /**
     * @Flow\Inject
     * @var RedirectService
     */
    protected $redirectService;

    /**
     * Redirect to a short identifier
     *
     * @param string $shortIdentifier
     * @param string $shortType
     * @return void
     * @throws PageNotFoundException
     * @throws StopActionException
     */
    public function redirectToPageAction(string $shortIdentifier, string $shortType = 'default'): void
    {
        $node = $this->redirectService->getNodeByShortIdentifierAndType($shortIdentifier, $shortType);

        if ($node instanceof NodeInterface) {
            $uri = $this->getPublicUriOfNode($node);

            $this->redirectToUri($uri);
        }

        throw new PageNotFoundException();
    }

    /**
     * Get the uri of a node
     *
     * @param NodeInterface $node
     * @return string
     */
    protected function getPublicUriOfNode(NodeInterface $node): string
    {
        return $this->linkingService->createNodeUri(
            $this->getControllerContext(),
            $node,
            null,
            'html',
            true
        );
    }
}
