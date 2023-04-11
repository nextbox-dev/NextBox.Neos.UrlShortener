<?php

namespace NextBox\Neos\UrlShortener\Services;

use GuzzleHttp\Psr7\ServerRequest;
use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequestFactory;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use NextBox\Neos\UrlShortener\Domain\Model\UrlShortener;
use NextBox\Neos\UrlShortener\Domain\Repository\UrlShortenerRepository;

/**
 * @Flow\Scope("singleton")
 */
class RedirectService
{

    /**
     * @Flow\Inject
     * @var UrlShortenerRepository
     */
    protected $urlShortenerRepository;

    /**
     * @Flow\Inject
     * @var ActionRequestFactory
     */
    protected $actionRequestFactory;

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
     * @Flow\Inject
     * @var BaseNodeServiceInterface
     */
    protected $baseNodeService;


    /**
     * Get a document node with an url-short-identifier
     *
     * @param string $shortIdentifier
     * @param string $shortType
     * @return NodeInterface|null
     * @throws \Neos\Flow\Exception
     */
    public function getNodeByShortIdentifierAndType(string $shortIdentifier, string $shortType = 'default'): ?NodeInterface
    {
        $urlShort = $this->urlShortenerRepository->findOneByShortIdentifierAndShortType($shortIdentifier, $shortType);

        if ($urlShort instanceof UrlShortener) {
            return $this->getNodeFromNodeData($urlShort->getNode(), $shortType);
        }

        $nodeType = $this->getNodeTypeOfType($shortType);
        $propertyName = $this->getPropertyNameOfType($shortType);
        $baseNode = $this->baseNodeService->getBaseNode($shortType, $urlShort->getNode());

        $node = (new FlowQuery([$baseNode]))
            ->find('[instanceof ' . $nodeType . '][' . $propertyName . '=' . $shortIdentifier . ']')
            ->get(0);

        if ($node instanceof NodeInterface) {
            $urlShort = new UrlShortener();
            $urlShort->setNode($node->getNodeData());
            $urlShort->setShortIdentifier($shortIdentifier);
            $urlShort->setShortType($shortType);

            $this->urlShortenerRepository->add($urlShort);
            $this->persistenceManager->persistAll();

            return $node;
        }

        return null;
    }

    /**
     * Create the short uri for a short identifier and a nodetype
     *
     * @param string $shortIdentifier
     * @param string $shortType
     * @return string
     */
    public function createShortUri(string $shortIdentifier, string $shortType = 'default'): string
    {
        $_SERVER['FLOW_REWRITEURLS'] = 1;

        $uriBuilder = new UriBuilder();
        $httpRequest = ServerRequest::fromGlobals();
        $request = $this->actionRequestFactory->createActionRequest($httpRequest);
        $uriBuilder->setRequest($request);
        $uriBuilder->setCreateAbsoluteUri(true);

        return $uriBuilder->uriFor(
            'redirectToPage',
            [
                'shortIdentifier' => $shortIdentifier,
                'shortType' => $shortType,
            ],
            'Redirect',
            'NextBox.Neos.UrlShortener'
        );
    }

    /**
     * Get the node type name of a type
     *
     * @param string $shortType
     * @return string
     * @throws \Neos\Flow\Exception
     */
    public function getNodeTypeOfType(string $shortType): string
    {
        if (key_exists($shortType, $this->typeSettings)) {
            return $this->typeSettings[$shortType]['nodeType'];
        }

        throw new \Neos\Flow\Exception('The short-type ' . $shortType . ' was not found in the configuration');
    }

    /**
     * Get the node type name of a type
     *
     * @param string $shortType
     * @return string
     * @throws \Neos\Flow\Exception
     */
    public function getPropertyNameOfType(string $shortType): string
    {
        if (key_exists($shortType, $this->typeSettings)) {
            return $this->typeSettings[$shortType]['property'];
        }

        throw new \Neos\Flow\Exception('The short-type ' . $shortType . ' was not found in the configuration');
    }

    /**
     * Get the live node from node data
     *
     * @param NodeData $nodeData
     * @param string $shortType
     * @return NodeInterface|null
     */
    protected function getNodeFromNodeData(NodeData $nodeData, string $shortType = 'default'): ?NodeInterface
    {
        return (new FlowQuery([$this->baseNodeService->getBaseNode($shortType, $nodeData)]))
            ->find('#' . $nodeData->getIdentifier())
            ->get(0);
    }
}
