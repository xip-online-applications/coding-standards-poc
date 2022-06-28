<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Content;

use Twig\Environment;

abstract class AbstractTwigContent implements ContentInterface
{
    public function __construct(
        private Environment $twig
    ) {
    }

    public function compose(): string
    {
        return $this->twig->render(
            $this->getTemplateName(),
            $this->getTemplateData()
        );
    }

    abstract protected function getTemplateName(): string;

    abstract protected function getTemplateData(): array;
}
