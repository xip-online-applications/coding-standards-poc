<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Model;

final class ResponseOptions
{
    public function __construct(
        private int $ttl = 60,
        private bool $mustRevalidate = false,
        private bool $noCache = false,
        private ?string $contentType = null
    ) {
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function mustRevalidate(): bool
    {
        return $this->mustRevalidate;
    }

    public function noCache(): bool
    {
        return $this->noCache;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }
}
