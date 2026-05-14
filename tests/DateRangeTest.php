<?php

namespace SebJean\ZodiacSignBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\DateRange;

class DateRangeTest extends TestCase
{
    public function testContainsDateInsideRange(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
        );

        $this->assertTrue($range->contains(new \DateTimeImmutable('2024-03-15')));
    }

    public function testContainsDateAtStart(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
        );

        $this->assertTrue($range->contains(new \DateTimeImmutable('2024-03-01')));
    }

    public function testContainsDateAtEnd(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
        );

        $this->assertTrue($range->contains(new \DateTimeImmutable('2024-03-31')));
    }

    public function testDoesNotContainDateBeforeRange(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
        );

        $this->assertFalse($range->contains(new \DateTimeImmutable('2024-02-29')));
    }

    public function testDoesNotContainDateAfterRange(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
        );

        $this->assertFalse($range->contains(new \DateTimeImmutable('2024-04-01')));
    }

    public function testExcludeEndIncludesDateBeforeEnd(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
            excludeEnd: true,
        );

        $this->assertTrue($range->contains(new \DateTimeImmutable('2024-03-30')));
    }

    public function testExcludeEndExcludesEndDate(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
            excludeEnd: true,
        );

        $this->assertFalse($range->contains(new \DateTimeImmutable('2024-03-31')));
    }

    public function testToString(): void
    {
        $range = new DateRange(
            new \DateTimeImmutable('2024-03-01'),
            new \DateTimeImmutable('2024-03-31'),
        );

        $this->assertSame('2024-03-01 - 2024-03-31', (string) $range);
    }
}
