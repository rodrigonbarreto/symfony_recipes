<?php

namespace App\EventListeners;

use App\Entity\HypermidiaResponse;
use App\Helper\CustomException\EntityFactoryException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityException', 1],
                ['handle404Exception', 0],
                ['handleGenericException', -1],
            ],
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if(!$this->checkApiPath($event)) return;

        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())
                ->getResponse();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            $event->setResponse($response);
        }
    }

    public function handleEntityException(ExceptionEvent $event)
    {
        if(!$this->checkApiPath($event)) return;

        if ($event->getThrowable() instanceof EntityFactoryException) {
            $response = HypermidiaResponse::fromError($event->getThrowable())
                ->getResponse();

            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        if(!$this->checkApiPath($event)) return;

        $response = HypermidiaResponse::fromError($event->getException());
        $event->setResponse($response->getResponse());
    }

    /**
     * @param ExceptionEvent $event
     * @return bool
     */
    public function checkApiPath(ExceptionEvent $event): bool
    {
        return stristr($event->getRequest()->getPathInfo(), 'api') == true;
    }
}
