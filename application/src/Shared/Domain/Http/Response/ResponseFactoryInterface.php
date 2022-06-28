<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Http\Response;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use XIP\Shared\Domain\Model\ResponseOptions;

interface ResponseFactoryInterface
{
    public function plainResponse(callable $data, ?ResponseOptions $responseOptions = null): Response;

    public function ttlResponse(callable $data, ?ResponseOptions $responseOptions = null): Response;
    
    public function lastModifiedResponse(
        callable $lastModified,
        callable $data,
        ?ResponseOptions $responseOptions = null
    ): Response;
    
    public function redirectResponse(string $url, ?ResponseOptions $responseOptions = null): RedirectResponse;
}
