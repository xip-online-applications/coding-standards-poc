<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Response;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use XIP\Shared\Domain\Http\Response\ResponseFactoryInterface;
use XIP\Shared\Domain\Model\ResponseOptions;

class ResponseFactory implements ResponseFactoryInterface
{
    public function plainResponse(callable $data, ?ResponseOptions $responseOptions = null): Response
    {
        // TODO: Implement plainResponse() method.
    }

    public function ttlResponse(callable $data, ?ResponseOptions $responseOptions = null): Response
    {
        // TODO: Implement ttlResponse() method.
    }

    public function lastModifiedResponse(
        callable $lastModified,
        callable $data,
        ?ResponseOptions $responseOptions = null
    ): Response {
        // TODO: Implement lastModifiedResponse() method.
    }

    public function redirectResponse(string $url, ?ResponseOptions $responseOptions = null): RedirectResponse
    {
        // TODO: Implement redirectResponse() method.
    }
}
