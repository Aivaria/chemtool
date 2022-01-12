<?php

namespace Chemtool\Webservice\Handler;

use Chemtool\Doctrine\Entities\Chemical;
use Chemtool\Doctrine\Entities\Tag;
use Doctrine\ORM\EntityManager;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Handles Frontend Tag functionality
 */
class TagHandler implements MiddlewareInterface
{
    /**
     * @param TemplateRendererInterface $renderer
     * @param EntityManager $entityManager
     */
    public function __construct(protected TemplateRendererInterface $renderer, protected EntityManager $entityManager)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function addPostAction(ServerRequestInterface $request)
    {
        $tagName = $request->getParsedBody()['name'];
        if ($this->entityManager->getRepository(Tag::class)->count(['name' => $tagName]) == 0) {
            $tag = new Tag();
            $tag->setName($tagName);
            $tag->setPriority((int)$request->getParsedBody()['priority']);
            $this->entityManager->persist($tag);
            $this->entityManager->flush($tag);
            $params = ['response' => 'success'];

        } else {
            $params = ['response' => 'already_exists'];
        }
        return $this->addGetAction($request, $params);
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $params
     * @return HtmlResponse
     */
    protected function addGetAction(ServerRequestInterface $request, $params = [])
    {
        return new HtmlResponse($this->renderer->render('tagtool::tagadd', $params));
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function editPostAction(ServerRequestInterface $request)
    {
        $tag = $this->entityManager->getRepository(Tag::class)->find($request->getAttribute('id', 0));
        $params = [];
        if (isset($tag)) {
            $tag->setName($request->getParsedBody()['name']);
            $tag->setPriority((int)$request->getParsedBody()['priority']);
            $tag->setHidden($request->getParsedBody()['hidden'] ?? false);
            $tag->setColor($request->getParsedBody()['color']);
            $this->entityManager->flush($tag);
            $params['response'] = 'success';
        } else {
            $params['response'] = 'not found';
        }
        return $this->editGetAction($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $params
     * @return HtmlResponse
     */
    protected function editGetAction(ServerRequestInterface $request, $params = [])
    {
        $tag = $this->entityManager->getRepository(Tag::class)->find($request->getAttribute('id', 0));
        if (isset($tag)) {
            $params['tag'] = $tag;
            return new HtmlResponse($this->renderer->render('tagtool::tagedit', $params));
        }
        return $this->listAction($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     */
    public function listAction(ServerRequestInterface $request)
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        return new HtmlResponse($this->renderer->render('tagtool::taglist', ['tags' => $tags]));
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAction(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'POST') {
            return $this->addPostAction($request);
        }
        return $this->addGetAction($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'POST') {
            return $this->editPostAction($request);
        }
        return $this->editGetAction($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(ServerRequestInterface $request)
    {
        $tag = $this->entityManager->getRepository(Tag::class)->find($request->getAttribute('id'));
        if ($tag != null) {
            $this->entityManager->remove($tag);
            $this->entityManager->flush($tag);
        }
        return $this->listAction($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $request->getAttribute('action', 'list');

        $response = match ($action) {
            'list' => $this->listAction($request),
            'add' => $this->addAction($request),
            'delete' => $this->deleteAction($request),
            'edit' => $this->editAction($request),
            default => new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND),
        };
        return $response;
    }
}