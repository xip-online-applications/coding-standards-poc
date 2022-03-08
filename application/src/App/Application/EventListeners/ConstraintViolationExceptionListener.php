<?php

declare(strict_types=1);

namespace XIP\App\Application\EventListeners;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use XIP\Shared\Domain\Exception\ConstraintViolationException;

class ConstraintViolationExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ConstraintViolationException) {
            return;
        }
        
        dd(
            $exception->getConstraintViolationList()
        );
    }
}