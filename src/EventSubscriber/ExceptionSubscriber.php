<?php

namespace App\EventSubscriber;

use PHPUnit\Util\Json;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
        $event->setResponse(match (true)
        {
            $exception instanceof \OutOfRangeException =>
            new JsonResponse($exception->getMessage(), 500),
            $exception instanceof \InvalidArgumentException || $exception instanceof NotFoundHttpException =>
            new JsonResponse('No data found at this route', 404),
            $exception instanceof HttpException =>
            new JsonResponse("An internal error has occured", 500),
            //default
            true =>
            new JsonResponse([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
                "exception" => $exception::class,
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
