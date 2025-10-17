<?php

namespace SebJean\ZodiacSignBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\Enum\ZodiacSign;
use SebJean\ZodiacSignBundle\ZodiacSignCalculator;

#[CoversClass(ZodiacSignCalculator::class)]
class ZodiacSignCalculatorTest extends TestCase
{
    private ZodiacSignCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new ZodiacSignCalculator();
    }

    #[DataProvider('zodiacSignProvider')]
    public function testGetZodiacSign(string $date, ZodiacSign $expectedSign): void
    {
        $inputs = [
            'string' => $date,
            'DateTime' => new \DateTime($date),
            'DateTimeImmutable' => new \DateTimeImmutable($date),
        ];

        foreach ($inputs as $type => $input) {
            $result = $this->calculator->calculateZodiacSign($input);
            $this->assertSame($expectedSign, $result, "Failed for input type: $type");
        }
    }

    #[DataProvider('zodiacSignTimestampProvider')]
    public function testGetZodiacSignFromTimestamp(int $timestamp, ZodiacSign $expectedSign): void
    {
        $result = $this->calculator->calculateZodiacSign($timestamp);

        $this->assertSame($expectedSign, $result);
    }

    public function testGetZodiacSignFromFloat(): void
    {
        $timestamp = 954028800.5;
        $result = $this->calculator->calculateZodiacSign($timestamp);

        $this->assertSame(ZodiacSign::Aries, $result);
    }

    public function testGetZodiacSignWithNullDefaultsToNow(): void
    {
        $result = $this->calculator->calculateZodiacSign(null);

        $this->assertContains($result, ZodiacSign::cases());
    }

    public function testGetZodiacSignWithoutParameterDefaultsToNow(): void
    {
        $result = $this->calculator->calculateZodiacSign();

        $this->assertContains($result, ZodiacSign::cases());
    }

    public function testGetZodiacSignWithInvalidDateFormat(): void
    {
        $this->expectException(\Exception::class);
        $this->calculator->calculateZodiacSign('invalid-date');
    }

    public static function zodiacSignProvider(): array
    {
        return [
            'Aries - start date (March 21)' => ['2000-03-21', ZodiacSign::Aries],
            'Aries - middle date (April 1)' => ['2000-04-01', ZodiacSign::Aries],
            'Aries - end date (April 19)' => ['2000-04-19', ZodiacSign::Aries],

            'Taurus - start date (April 20)' => ['2000-04-20', ZodiacSign::Taurus],
            'Taurus - middle date (May 1)' => ['2000-05-01', ZodiacSign::Taurus],
            'Taurus - end date (May 20)' => ['2000-05-20', ZodiacSign::Taurus],

            'Gemini - start date (May 21)' => ['2000-05-21', ZodiacSign::Gemini],
            'Gemini - middle date (June 1)' => ['2000-06-01', ZodiacSign::Gemini],
            'Gemini - end date (June 20)' => ['2000-06-20', ZodiacSign::Gemini],

            'Cancer - start date (June 21)' => ['2000-06-21', ZodiacSign::Cancer],
            'Cancer - middle date (July 1)' => ['2000-07-01', ZodiacSign::Cancer],
            'Cancer - end date (July 22)' => ['2000-07-22', ZodiacSign::Cancer],

            'Leo - start date (July 23)' => ['2000-07-23', ZodiacSign::Leo],
            'Leo - middle date (August 1)' => ['2000-08-01', ZodiacSign::Leo],
            'Leo - end date (August 22)' => ['2000-08-22', ZodiacSign::Leo],

            'Virgo - start date (August 23)' => ['2000-08-23', ZodiacSign::Virgo],
            'Virgo - middle date (September 1)' => ['2000-09-01', ZodiacSign::Virgo],
            'Virgo - end date (September 22)' => ['2000-09-22', ZodiacSign::Virgo],

            'Libra - start date (September 23)' => ['2000-09-23', ZodiacSign::Libra],
            'Libra - middle date (October 1)' => ['2000-10-01', ZodiacSign::Libra],
            'Libra - end date (October 22)' => ['2000-10-22', ZodiacSign::Libra],

            'Scorpio - start date (October 23)' => ['2000-10-23', ZodiacSign::Scorpio],
            'Scorpio - middle date (November 1)' => ['2000-11-01', ZodiacSign::Scorpio],
            'Scorpio - end date (November 21)' => ['2000-11-21', ZodiacSign::Scorpio],

            'Sagittarius - start date (November 22)' => ['2000-11-22', ZodiacSign::Sagittarius],
            'Sagittarius - middle date (December 1)' => ['2000-12-01', ZodiacSign::Sagittarius],
            'Sagittarius - end date (December 21)' => ['2000-12-21', ZodiacSign::Sagittarius],

            'Capricorn - start date (December 22)' => ['2000-12-22', ZodiacSign::Capricorn],
            'Capricorn - middle date (January 1)' => ['2000-01-01', ZodiacSign::Capricorn],
            'Capricorn - end date (January 19)' => ['2000-01-19', ZodiacSign::Capricorn],

            'Aquarius - start date (January 20)' => ['2000-01-20', ZodiacSign::Aquarius],
            'Aquarius - middle date (February 1)' => ['2000-02-01', ZodiacSign::Aquarius],
            'Aquarius - end date (February 18)' => ['2000-02-18', ZodiacSign::Aquarius],

            'Pisces - start date (February 19)' => ['2000-02-19', ZodiacSign::Pisces],
            'Pisces - middle date (March 1)' => ['2000-03-01', ZodiacSign::Pisces],
            'Pisces - end date (March 20)' => ['2000-03-20', ZodiacSign::Pisces],
        ];
    }

    public static function zodiacSignTimestampProvider(): array
    {
        return [
            'Aries (March 25, 2000)' => [954028800, ZodiacSign::Aries],
            'Taurus (May 1, 2000)' => [957139200, ZodiacSign::Taurus],
            'Gemini (June 1, 2000)' => [959817600, ZodiacSign::Gemini],
            'Cancer (July 1, 2000)' => [962409600, ZodiacSign::Cancer],
            'Leo (August 1, 2000)' => [965088000, ZodiacSign::Leo],
            'Virgo (September 1, 2000)' => [967766400, ZodiacSign::Virgo],
            'Libra (October 1, 2000)' => [970358400, ZodiacSign::Libra],
            'Scorpio (November 1, 2000)' => [973036800, ZodiacSign::Scorpio],
            'Sagittarius (December 1, 2000)' => [975628800, ZodiacSign::Sagittarius],
            'Capricorn (January 1, 2000)' => [946684800, ZodiacSign::Capricorn],
            'Aquarius (February 1, 2000)' => [949363200, ZodiacSign::Aquarius],
            'Pisces (March 1, 2000)' => [951868800, ZodiacSign::Pisces],
        ];
    }

    public function testBoundaryDates(): void
    {
        $this->assertSame(ZodiacSign::Pisces, $this->calculator->calculateZodiacSign('2000-03-20'));
        $this->assertSame(ZodiacSign::Aries, $this->calculator->calculateZodiacSign('2000-03-21'));

        $this->assertSame(ZodiacSign::Aries, $this->calculator->calculateZodiacSign('2000-04-19'));
        $this->assertSame(ZodiacSign::Taurus, $this->calculator->calculateZodiacSign('2000-04-20'));
    }

    public function testLeapYearDate(): void
    {
        $this->assertSame(ZodiacSign::Pisces, $this->calculator->calculateZodiacSign('2000-02-29'));
    }

    public function testDifferentYears(): void
    {
        $this->assertSame(ZodiacSign::Leo, $this->calculator->calculateZodiacSign('1990-08-01'));
        $this->assertSame(ZodiacSign::Leo, $this->calculator->calculateZodiacSign('2020-08-01'));
        $this->assertSame(ZodiacSign::Leo, $this->calculator->calculateZodiacSign('2050-08-01'));
    }
}
