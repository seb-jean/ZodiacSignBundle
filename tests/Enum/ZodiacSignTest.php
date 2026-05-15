<?php

namespace SebJean\ZodiacSignBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\DateRange;
use SebJean\ZodiacSignBundle\Enum\ZodiacSign;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

#[CoversClass(ZodiacSign::class)]
class ZodiacSignTest extends TestCase
{
    #[DataProvider('enumValueProvider')]
    public function testEnumValues(ZodiacSign $sign, string $expectedValue): void
    {
        $this->assertSame($expectedValue, $sign->value);
    }

    #[DataProvider('symbolProvider')]
    public function testGetSymbol(ZodiacSign $sign, string $expectedSymbol): void
    {
        $this->assertSame($expectedSymbol, $sign->getSymbol());
    }

    #[DataProvider('dateRangeProvider')]
    public function testDateRanges(ZodiacSign $sign, string $expectedStart, string $expectedEnd): void
    {
        $this->assertSame($expectedStart, $sign->getStartDate()->format('m-d'));
        $this->assertSame($expectedEnd, $sign->getEndDate()->format('m-d'));
    }

    /** @return array<string, array{ZodiacSign, string}> */
    public static function enumValueProvider(): array
    {
        return [
            'Aries' => [ZodiacSign::Aries, 'aries'],
            'Taurus' => [ZodiacSign::Taurus, 'taurus'],
            'Gemini' => [ZodiacSign::Gemini, 'gemini'],
            'Cancer' => [ZodiacSign::Cancer, 'cancer'],
            'Leo' => [ZodiacSign::Leo, 'leo'],
            'Virgo' => [ZodiacSign::Virgo, 'virgo'],
            'Libra' => [ZodiacSign::Libra, 'libra'],
            'Scorpio' => [ZodiacSign::Scorpio, 'scorpio'],
            'Sagittarius' => [ZodiacSign::Sagittarius, 'sagittarius'],
            'Capricorn' => [ZodiacSign::Capricorn, 'capricorn'],
            'Aquarius' => [ZodiacSign::Aquarius, 'aquarius'],
            'Pisces' => [ZodiacSign::Pisces, 'pisces'],
        ];
    }

    /** @return array<string, array{ZodiacSign, string}> */
    public static function symbolProvider(): array
    {
        return [
            'Aries' => [ZodiacSign::Aries, '♈'],
            'Taurus' => [ZodiacSign::Taurus, '♉'],
            'Gemini' => [ZodiacSign::Gemini, '♊'],
            'Cancer' => [ZodiacSign::Cancer, '♋'],
            'Leo' => [ZodiacSign::Leo, '♌'],
            'Virgo' => [ZodiacSign::Virgo, '♍'],
            'Libra' => [ZodiacSign::Libra, '♎'],
            'Scorpio' => [ZodiacSign::Scorpio, '♏'],
            'Sagittarius' => [ZodiacSign::Sagittarius, '♐'],
            'Capricorn' => [ZodiacSign::Capricorn, '♑'],
            'Aquarius' => [ZodiacSign::Aquarius, '♒'],
            'Pisces' => [ZodiacSign::Pisces, '♓'],
        ];
    }

    /** @return array<string, array{ZodiacSign, string, string}> */
    public static function dateRangeProvider(): array
    {
        return [
            'Aquarius' => [ZodiacSign::Aquarius, '01-20', '02-18'],
            'Pisces' => [ZodiacSign::Pisces, '02-19', '03-20'],
            'Aries' => [ZodiacSign::Aries, '03-21', '04-19'],
            'Taurus' => [ZodiacSign::Taurus, '04-20', '05-20'],
            'Gemini' => [ZodiacSign::Gemini, '05-21', '06-20'],
            'Cancer' => [ZodiacSign::Cancer, '06-21', '07-22'],
            'Leo' => [ZodiacSign::Leo, '07-23', '08-22'],
            'Virgo' => [ZodiacSign::Virgo, '08-23', '09-22'],
            'Libra' => [ZodiacSign::Libra, '09-23', '10-22'],
            'Scorpio' => [ZodiacSign::Scorpio, '10-23', '11-21'],
            'Sagittarius' => [ZodiacSign::Sagittarius, '11-22', '12-21'],
            'Capricorn' => [ZodiacSign::Capricorn, '12-22', '01-19'],
        ];
    }

    public function testGetPeriodReturnsCorrectRange(): void
    {
        $period = ZodiacSign::Aries->getPeriod();

        $this->assertInstanceOf(DateRange::class, $period);
        $this->assertSame('03-21', $period->getStart()->format('m-d'));
        $this->assertSame('04-19', $period->getEnd()->format('m-d'));
    }

    public function testGetPeriodContainsDate(): void
    {
        $period = ZodiacSign::Leo->getPeriod();

        // DateRange uses year-agnostic dates (1970 as reference): compare m-d only
        $this->assertTrue($period->contains(new \DateTimeImmutable('1970-08-01')));
        $this->assertFalse($period->contains(new \DateTimeImmutable('1970-09-01')));
    }

    public function testTrans(): void
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());

        $translations = [];
        foreach (ZodiacSign::cases() as $sign) {
            $translations['zodiac_sign.'.$sign->value] = ucfirst($sign->value);
        }

        $translator->addResource('array', $translations, 'en', 'ZodiacSignBundle');

        foreach (ZodiacSign::cases() as $sign) {
            $this->assertSame(ucfirst($sign->value), $sign->trans($translator));
        }
    }

    public function testTransWithLocale(): void
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', [
            'zodiac_sign.aries' => 'Aries',
        ], 'en', 'ZodiacSignBundle');
        $translator->addResource('array', [
            'zodiac_sign.aries' => 'Bélier',
        ], 'fr', 'ZodiacSignBundle');

        $this->assertSame('Bélier', ZodiacSign::Aries->trans($translator, 'fr'));
    }

    public function testAllCasesArePresent(): void
    {
        $cases = ZodiacSign::cases();
        $this->assertCount(12, $cases);
    }

    #[DataProvider('dateRangeProviderForContains')]
    public function testContains(ZodiacSign $sign, string $date, bool $shouldContain): void
    {
        $datePoint = new DatePoint($date);
        $this->assertSame($shouldContain, $sign->contains($datePoint));
    }

    /** @return array<string, array{ZodiacSign, string, bool}> */
    public static function dateRangeProviderForContains(): array
    {
        return [
            'Capricorn contains start date' => [ZodiacSign::Capricorn, '2020-12-22', true],
            'Capricorn contains end date' => [ZodiacSign::Capricorn, '2020-01-19', true],
            'Capricorn contains middle date (Jan)' => [ZodiacSign::Capricorn, '2020-01-10', true],
            'Capricorn contains middle date (Dec)' => [ZodiacSign::Capricorn, '2020-12-25', true],
            'Capricorn does not contain before start' => [ZodiacSign::Capricorn, '2020-12-21', false],
            'Capricorn does not contain after end' => [ZodiacSign::Capricorn, '2020-01-20', false],

            'Aries contains start date' => [ZodiacSign::Aries, '2020-03-21', true],
            'Aries contains end date' => [ZodiacSign::Aries, '2020-04-19', true],
            'Aries does not contain before start' => [ZodiacSign::Aries, '2020-03-20', false],
            'Aries does not contain after end' => [ZodiacSign::Aries, '2020-04-20', false],
        ];
    }
}
