<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Content;

use Twig\Environment;

abstract class AbstractTwigContent
{
    public function __construct(
        protected Environment $twig
    ) {
    }
}
