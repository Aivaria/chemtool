<?php

namespace Chemtool\Webservice\Handler;

use Chemtool\Doctrine\Entities\Chemical;
use Chemtool\Doctrine\Entities\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChemtoolHandler implements MiddlewareInterface
{
    public function __construct(protected TemplateRendererInterface $renderer, protected EntityManager $entityManager)
    {
    }

    public function listAction(ServerRequestInterface $request)
    {
        $chemicals = $this->entityManager->getRepository(Chemical::class)->findAll();
        return new HtmlResponse($this->renderer->render('chemtool::chemlist', ['chemicals' => $chemicals]));
    }

    public function viewAction(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render('chemtool::chemview', []));
    }

    protected function editPostAction(ServerRequestInterface $request)
    {
        /**
         * @var Chemical $chemical
         */
        $chemical = $this->entityManager->getRepository(Chemical::class)->find($request->getAttribute('id', 0));
        $params = [];
        if (isset($request->getParsedBody()['baseinformation'])) {
            if (isset($chemical)) {
                $chemical->setName($request->getParsedBody()['name']);
                $chemical->setRequireHeat((int)$request->getParsedBody()['heat']);
                $chemical->setProduced((int)$request->getParsedBody()['produced']);
                $this->entityManager->flush($chemical);
                $params['response'] = 'success';
            } else {
                $params['response'] = 'not found';
            }
        }
        if (isset($request->getParsedBody()['submittags'])) {
            $tagIds = array_keys($request->getParsedBody()['tags'] ?? []);
            $tags = $this->entityManager->getRepository(Tag::class)->findBy(['id' => $tagIds]);
            $chemical->setTags(new ArrayCollection($tags));
            $this->entityManager->flush($chemical);
        }

        return $this->editGetAction($request);
    }

    protected function editGetAction(ServerRequestInterface $request, $params = [])
    {
        $chemical = $this->entityManager->getRepository(Chemical::class)->find($request->getAttribute('id', 0));
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        if (isset($chemical)) {
            $params['chemical'] = $chemical;
            $params['tags'] = $tags;
            return new HtmlResponse($this->renderer->render('chemtool::chemedit', $params));
        }
        return $this->listAction($request);
    }

    protected function addPostAction(ServerRequestInterface $request)
    {
        /**
         * @var Chemical $chemical
         */
        $chemical = new Chemical();
        $params = [];
        $chemical->setName($request->getParsedBody()['name']);
        $chemical->setIdName($request->getParsedBody()['idname']);
        $chemical->setRequireHeat((int)$request->getParsedBody()['heat']);
        $chemical->setProduced((int)$request->getParsedBody()['produced']);
        $this->entityManager->persist($chemical);
        $this->entityManager->flush($chemical);
        $params['response'] = 'success';
        return $this->addGetAction($request);
    }

    protected function addGetAction(ServerRequestInterface $request, $params = [])
    {
        return new HtmlResponse($this->renderer->render('chemtool::chemadd', $params));
    }


    public function editAction(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'POST') {
            return $this->editPostAction($request);
        }
        return $this->editGetAction($request);
    }

    public function addAction(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'POST') {
            return $this->addPostAction($request);
        }
        return $this->addGetAction($request);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $request->getAttribute('action', 'list');

        $response = match ($action) {
            'view' => $this->viewAction($request),
            'list' => $this->listAction($request),
            'add' => $this->addAction($request),
            'edit' => $this->editAction($request),
            default => new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND),
        };
        return $response;
    }


}