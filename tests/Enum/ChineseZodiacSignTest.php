<?php

namespace SebJean\ZodiacSignBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\DateRange;
use SebJean\ZodiacSignBundle\Enum\ChineseZodiacSign;

class ChineseZodiacSignTest extends TestCase
{
    public function testAllTwelveCasesExist(): void
    {
        $cases = ChineseZodiacSign::cases();

        $this->assertCount(12, $cases);
    }

    public function testCaseValues(): void
    {
        $this->assertSame('rat', ChineseZodiacSign::Rat->value);
        $this->assertSame('ox', ChineseZodiacSign::Ox->value);
        $this->assertSame('tiger', ChineseZodiacSign::Tiger->value);
        $this->assertSame('rabbit', ChineseZodiacSign::Rabbit->value);
        $this->assertSame('dragon', ChineseZodiacSign::Dragon->value);
        $this->assertSame('snake', ChineseZodiacSign::Snake->value);
        $this->assertSame('horse', ChineseZodiacSign::Horse->value);
        $this->assertSame('goat', ChineseZodiacSign::Goat->value);
        $this->assertSame('monkey', ChineseZodiacSign::Monkey->value);
        $this->assertSame('rooster', ChineseZodiacSign::Rooster->value);
        $this->assertSame('dog', ChineseZodiacSign::Dog->value);
        $this->assertSame('pig', ChineseZodiacSign::Pig->value);
    }

    public function testGetPeriodReturnsCorrectRange(): void
    {
        // Dragon year 2024: Feb 10, 2024 → Feb 9, 2025 (excludeEnd)
        $period = ChineseZodiacSign::Dragon->getPeriod(2024);

        $this->assertInstanceOf(DateRange::class, $period);
        $this->assertTrue($period->contains(new \DateTimeImmutable('2024-02-10')));
        $this->assertTrue($period->contains(new \DateTimeImmutable('2024-12-31')));
        $this->assertFalse($period->contains(new \DateTimeImmutable('2024-02-09')));
        $this->assertFalse($period->contains(new \DateTimeImmutable('2025-01-29'))); // Snake starts
    }

    public function testGetPeriodWithoutYearUsesCurrentYear(): void
    {
        $currentYear = (int) (new \DateTimeImmutable())->format('Y');
        $currentSign = (new \SebJean\ZodiacSignBundle\ChineseZodiacCalculator())->calculateChineseZodiacSign();

        $period = $currentSign->getPeriod();

        $this->assertInstanceOf(DateRange::class, $period);
        $this->assertTrue($period->contains(new \DateTimeImmutable('now')));
    }

    public function testGetPeriodWorksWhenSignSpillsIntoNextYear(): void
    {
        // Dragon 2024 starts Feb 10, 2024, so asking for Dragon in 2025 (before CNY 2025)
        // should return the same period
        $period = ChineseZodiacSign::Dragon->getPeriod(2025);

        $this->assertTrue($period->contains(new \DateTimeImmutable('2024-06-01')));
    }

    public function testGetPeriodThrowsForInvalidYear(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        // Rooster does not appear in 2024 (Dragon) or 2025 (Snake)
        ChineseZodiacSign::Rooster->getPeriod(2025);
    }

    public function testGetSymbol(): void
    {
        $this->assertSame('🐀', ChineseZodiacSign::Rat->getSymbol());
        $this->assertSame('🐂', ChineseZodiacSign::Ox->getSymbol());
        $this->assertSame('🐅', ChineseZodiacSign::Tiger->getSymbol());
        $this->assertSame('🐇', ChineseZodiacSign::Rabbit->getSymbol());
        $this->assertSame('🐉', ChineseZodiacSign::Dragon->getSymbol());
        $this->assertSame('🐍', ChineseZodiacSign::Snake->getSymbol());
        $this->assertSame('🐎', ChineseZodiacSign::Horse->getSymbol());
        $this->assertSame('🐐', ChineseZodiacSign::Goat->getSymbol());
        $this->assertSame('🐒', ChineseZodiacSign::Monkey->getSymbol());
        $this->assertSame('🐓', ChineseZodiacSign::Rooster->getSymbol());
        $this->assertSame('🐕', ChineseZodiacSign::Dog->getSymbol());
        $this->assertSame('🐖', ChineseZodiacSign::Pig->getSymbol());
    }
}
