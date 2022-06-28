<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\Shared\Domain\Model;

use PHPUnit\Framework\TestCase;
use XIP\Shared\Domain\Model\ResponseOptions;

/**
 * @covers \XIP\Shared\Domain\Model\ResponseOptions
 */
class ResponseOptionsTest extends TestCase
{
    public function testDefaults(): void
    {
        // Execute
        $result = new ResponseOptions();
        
        // Validate
        $this->assertInstanceOf(ResponseOptions::class, $result);
        $this->assertSame(60, $result->getTtl());
        $this->assertFalse($result->mustRevalidate());
        $this->assertFalse($result->noCache());
        $this->assertNull($result->getContentType());
    }
    
    public function responseOptionsScenarioProvider(): array
    {
        return [
            'different ttl' => [
                'ttl' => 120,
                'noCache' => false,
                'mustRevalidate' => false,
                'contentType' => null,
            ],
            'no cache' => [
                'ttl' => 60,
                'noCache' => true,
                'mustRevalidate' => false,
                'contentType' => null,
            ],
            'must revalidate' => [
                'ttl' => 60,
                'noCache' => false,
                'mustRevalidate' => true,
                'contentType' => null,
            ],
            'content type' => [
                'ttl' => 60,
                'noCache' => false,
                'mustRevalidate' => false,
                'contentType' => 'text/html; charset=UTF-8',
            ],
        ];
    }

    /**
     * @dataProvider responseOptionsScenarioProvider
     */
    public function testResponseOptions(int $ttl, bool $noCache, bool $mustRevalidate, ?string $contentType): void
    {
        // Execute
        $result = new ResponseOptions($ttl, $mustRevalidate, $noCache, $contentType);
        
        // Validate
        $this->assertInstanceOf(ResponseOptions::class, $result);
        $this->assertSame($ttl, $result->getTtl());
        $this->assertSame($mustRevalidate, $result->mustRevalidate());
        $this->assertSame($noCache, $result->noCache());
        $this->assertSame($contentType, $result->getContentType());
    }
}
