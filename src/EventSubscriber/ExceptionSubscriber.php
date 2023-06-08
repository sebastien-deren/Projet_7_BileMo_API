<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof HttpException)) {
            $event->setResponse(new JsonResponse($exception->getMessage() . $exception::class, 500));
            return;
        }
        if($exception instanceof NotFoundHttpException){
            $event->setResponse(new JsonResponse($exception->getMessage(),404));
            return;
        }
       $data = [
            'status' => $exception->getCode(),
            'message' => $exception->getMessage(),
            "exception"=> $exception::class,
        ];
        $event->setResponse(new JsonResponse($data));

    }

    public static function getSubscribedEvents(): array
    {
        return [
           // KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
