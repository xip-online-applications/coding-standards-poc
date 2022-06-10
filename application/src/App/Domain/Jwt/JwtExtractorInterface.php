<?php

declare(strict_types=1);

namespace XIP\App\Domain\Jwt;

interface JwtExtractorInterface
{
    public function extract(): ?string;
}
