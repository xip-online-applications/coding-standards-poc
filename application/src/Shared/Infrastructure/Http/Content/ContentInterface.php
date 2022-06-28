<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Content;

interface ContentInterface
{
    public function compose(): string;
}