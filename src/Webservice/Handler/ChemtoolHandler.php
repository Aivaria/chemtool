<?php

namespace Chemtool\Webservice\Handler;

use Chemtool\Doctrine\Entities\Chemical;
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
        return new HtmlResponse($this->renderer->render('chemtool::chemlist', ['chemicals'=>$chemicals]));
    }

    public function viewAction(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render('chemtool::chemview', []));
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $request->getAttribute('action', 'list');

        $response = match ($action) {
            'view' => $this->viewAction($request),
            'list' => $this->listAction($request),
//            'add'=>$this->addAction($request),
//            'edit'=>$this->editAction($request),
            default => new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND),
        };
        return $response;
    }


}