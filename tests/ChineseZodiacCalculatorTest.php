<?php

namespace SebJean\ZodiacSignBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\ChineseZodiacCalculator;
use SebJean\ZodiacSignBundle\Enum\ChineseZodiacSign;

class ChineseZodiacCalculatorTest extends TestCase
{
    private ChineseZodiacCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ChineseZodiacCalculator();
    }

    #[DataProvider('dateSignProvider')]
    public function testCalculateChineseZodiacSignFromString(string $date, ChineseZodiacSign $expected): void
    {
        $this->assertSame($expected, $this->calculator->calculateChineseZodiacSign($date));
    }

    #[DataProvider('dateSignProvider')]
    public function testCalculateChineseZodiacSignFromDateTimeInterface(string $date, ChineseZodiacSign $expected): void
    {
        $this->assertSame($expected, $this->calculator->calculateChineseZodiacSign(new \DateTimeImmutable($date)));
    }

    public function testCalculateChineseZodiacSignFromTimestamp(): void
    {
        // 2024-02-10 = Dragon year starts
        $timestamp = mktime(0, 0, 0, 2, 10, 2024);
        $this->assertSame(ChineseZodiacSign::Dragon, $this->calculator->calculateChineseZodiacSign($timestamp));
    }

    public function testCalculateChineseZodiacSignFromFloatTimestamp(): void
    {
        $timestamp = (float) mktime(0, 0, 0, 2, 10, 2024);
        $this->assertSame(ChineseZodiacSign::Dragon, $this->calculator->calculateChineseZodiacSign($timestamp));
    }

    public function testCalculateChineseZodiacSignFromNull(): void
    {
        $result = $this->calculator->calculateChineseZodiacSign(null);
        $this->assertInstanceOf(ChineseZodiacSign::class, $result);
    }

    public static function dateSignProvider(): array
    {
        return [
            // On Chinese New Year → new sign starts
            'CNY 1990 (Horse starts Jan 27)' => ['1990-01-27', ChineseZodiacSign::Horse],
            'CNY 2024 (Dragon starts Feb 10)' => ['2024-02-10', ChineseZodiacSign::Dragon],
            'CNY 2025 (Snake starts Jan 29)' => ['2025-01-29', ChineseZodiacSign::Snake],

            // Day before CNY → previous year sign still active
            'Day before CNY 1990 → Snake' => ['1990-01-26', ChineseZodiacSign::Snake],
            'Day before CNY 2024 → Rabbit' => ['2024-02-09', ChineseZodiacSign::Rabbit],
            'Day before CNY 2025 → Dragon' => ['2025-01-28', ChineseZodiacSign::Dragon],

            // Mid-year dates
            'Mid 2024 → Dragon' => ['2024-06-15', ChineseZodiacSign::Dragon],
            'End of 2024 → Dragon' => ['2024-12-31', ChineseZodiacSign::Dragon],

            // Verify all 12 signs appear across a 12-year cycle
            'Rat (2020)' => ['2020-02-01', ChineseZodiacSign::Rat],
            'Ox (2021)' => ['2021-03-01', ChineseZodiacSign::Ox],
            'Tiger (2022)' => ['2022-03-01', ChineseZodiacSign::Tiger],
            'Rabbit (2023)' => ['2023-03-01', ChineseZodiacSign::Rabbit],
            'Dragon (2024)' => ['2024-03-01', ChineseZodiacSign::Dragon],
            'Snake (2025)' => ['2025-03-01', ChineseZodiacSign::Snake],
            'Horse (2026)' => ['2026-03-01', ChineseZodiacSign::Horse],
            'Goat (2027)' => ['2027-03-01', ChineseZodiacSign::Goat],
            'Monkey (2028)' => ['2028-03-01', ChineseZodiacSign::Monkey],
            'Rooster (2029)' => ['2029-03-01', ChineseZodiacSign::Rooster],
            'Dog (2030)' => ['2030-03-01', ChineseZodiacSign::Dog],
            'Pig (2031)' => ['2031-03-01', ChineseZodiacSign::Pig],
        ];
    }
}
