<?php

namespace Chemtool\Webservice\Handler;

use Chemtool\Doctrine\Entities\Chemical;
use Chemtool\Doctrine\Entities\Tag;
use Doctrine\ORM\EntityManager;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TagHandler implements MiddlewareInterface
{
    public function __construct(protected TemplateRendererInterface $renderer, protected EntityManager $entityManager)
    {
    }

    protected function addPostAction(ServerRequestInterface $request)
    {
        $tagName = $request->getParsedBody()['name'];
        if ($this->entityManager->getRepository(Tag::class)->count(['name' => $tagName]) == 0) {
            $tag = new Tag();
            $tag->setName($request->getParsedBody()['name']);
            $this->entityManager->persist($tag);
            $this->entityManager->flush($tag);
            $params = ['response'=>'success'];

        }else{
            $params = ['response'=>'already_exists'];
        }
        return $this->addGetAction($request, $params);
    }

    protected function addGetAction(ServerRequestInterface $request, $params=[])
    {
        return new HtmlResponse($this->renderer->render('tagtool::tagadd', $params));
    }

    public function listAction(ServerRequestInterface $request)
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        return new HtmlResponse($this->renderer->render('tagtool::taglist', ['tags' => $tags]));
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
        $tagId = $request->getAttribute('id', null);

        $response = match ($action) {
            'list' => $this->listAction($request),
            'add' => $this->addAction($request),
//            'edit'=>$this->editAction($request),
            default => new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND),
        };
        return $response;
    }
}