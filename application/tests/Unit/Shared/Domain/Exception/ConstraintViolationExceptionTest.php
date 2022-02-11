<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\Shared\Domain\Exception;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use XIP\Shared\Domain\Exception\ConstraintViolationException;

/**
 * @covers \XIP\Shared\Domain\Exception\ConstraintViolationException
 */
class ConstraintViolationExceptionTest extends TestCase
{
    private MockObject|ConstraintViolationListInterface $constraintViolationListMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraintViolationListMock = $this->createMock(ConstraintViolationListInterface::class);
    }

    /**
     * @covers \XIP\Shared\Domain\Exception\ConstraintViolationException::create
     */
    public function testCreate(): void
    {
        // Execute
        $result = ConstraintViolationException::create($this->constraintViolationListMock);

        // Validate
        $this->assertInstanceOf(ConstraintViolationException::class, $result);
    }

    /**
     * @covers \XIP\Shared\Domain\Exception\ConstraintViolationException::getConstraintViolationList
     */
    public function testGetConstraintViolationList(): void
    {
        // Setup
        $constraintViolationException = ConstraintViolationException::create($this->constraintViolationListMock);

        // Execute
        $result = $constraintViolationException->getConstraintViolationList();

        // Validate
        $this->assertSame($this->constraintViolationListMock, $result);
    }
}
