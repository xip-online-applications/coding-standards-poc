<?php

declare(strict_types=1);

namespace XIP\Tests\TestData\Factory;

use Faker\Factory as GeneratorFactory;
use Faker\Generator;

abstract class AbstractFactory
{
    private const LOCALE = 'nl_NL';

    protected Generator $generator;

    public function __construct()
    {
        $this->generator = GeneratorFactory::create(self::LOCALE);
    }

    public function getGenerator(): Generator
    {
        return $this->generator;
    }
}
