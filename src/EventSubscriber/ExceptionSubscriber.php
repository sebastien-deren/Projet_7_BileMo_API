<?php

namespace App\EventSubscriber;

use PHPUnit\Util\Json;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof HttpException)) {

            $this->logger->error($exception->getMessage() . " error code: " . $exception->getCode());
        }
        $event->setResponse(match (true) {
            $exception instanceof \OutOfRangeException =>
            new JsonResponse($exception->getMessage(), 500),
            $exception instanceof \InvalidArgumentException || $exception instanceof NotFoundHttpException => new JsonResponse('No data found at this route ', 404),
            $exception instanceof BadRequestHttpException => new jsonResponse($exception->getMessage(), 400,[],true),
            $exception->getCode() === 500 =>
            new JsonResponse("An internal error has occurred,please contact us if the problem persist.", 500),
            true =>
            new JsonResponse([
                $exception->getMessage(),
            ])
        }
        );


    }

    public
    static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
