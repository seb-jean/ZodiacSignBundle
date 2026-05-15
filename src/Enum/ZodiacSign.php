<?php

namespace SebJean\ZodiacSignBundle\Enum;

use SebJean\ZodiacSignBundle\DateRange;
use Symfony\Component\Clock\DatePoint;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ZodiacSign: string implements TranslatableInterface
{
    case Aries = 'aries';
    case Taurus = 'taurus';
    case Gemini = 'gemini';
    case Cancer = 'cancer';
    case Leo = 'leo';
    case Virgo = 'virgo';
    case Libra = 'libra';
    case Scorpio = 'scorpio';
    case Sagittarius = 'sagittarius';
    case Capricorn = 'capricorn';
    case Aquarius = 'aquarius';
    case Pisces = 'pisces';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('zodiac_sign.'.$this->value, domain: 'ZodiacSignBundle', locale: $locale);
    }

    public function getSymbol(): string
    {
        return match ($this) {
            self::Aries => '♈',
            self::Taurus => '♉',
            self::Gemini => '♊',
            self::Cancer => '♋',
            self::Leo => '♌',
            self::Virgo => '♍',
            self::Libra => '♎',
            self::Scorpio => '♏',
            self::Sagittarius => '♐',
            self::Capricorn => '♑',
            self::Aquarius => '♒',
            self::Pisces => '♓',
        };
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return DatePoint::createFromFormat('!m-d', $this->getRangeDate()['start'], new \DateTimeZone('UTC'));
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return DatePoint::createFromFormat('!m-d', $this->getRangeDate()['end'], new \DateTimeZone('UTC'));
    }

    /** @return array{start: string, end: string} */
    private function getRangeDate(): array
    {
        return match ($this) {
            self::Aquarius => ['start' => '01-20', 'end' => '02-18'],
            self::Pisces => ['start' => '02-19', 'end' => '03-20'],
            self::Aries => ['start' => '03-21', 'end' => '04-19'],
            self::Taurus => ['start' => '04-20', 'end' => '05-20'],
            self::Gemini => ['start' => '05-21', 'end' => '06-20'],
            self::Cancer => ['start' => '06-21', 'end' => '07-22'],
            self::Leo => ['start' => '07-23', 'end' => '08-22'],
            self::Virgo => ['start' => '08-23', 'end' => '09-22'],
            self::Libra => ['start' => '09-23', 'end' => '10-22'],
            self::Scorpio => ['start' => '10-23', 'end' => '11-21'],
            self::Sagittarius => ['start' => '11-22', 'end' => '12-21'],
            self::Capricorn => ['start' => '12-22', 'end' => '01-19'],
        };
    }

    public function getPeriod(?int $year = null): DateRange
    {
        $year ??= (int) (new DatePoint())->format('Y');
        $range = $this->getRangeDate();

        // Capricorn spans the year boundary: Dec 22 → Jan 19 of the following year
        $endYear = self::Capricorn === $this ? $year + 1 : $year;

        $start = DatePoint::createFromFormat('!Y-m-d', \sprintf('%d-%s', $year, $range['start']), new \DateTimeZone('UTC'));
        $end = DatePoint::createFromFormat('!Y-m-d', \sprintf('%d-%s', $endYear, $range['end']), new \DateTimeZone('UTC'));

        return new DateRange($start, $end);
    }

    public function contains(DatePoint $originalDatePoint): bool
    {
        $datePoint = DatePoint::createFromFormat('!m-d', $originalDatePoint->format('m-d'), new \DateTimeZone('UTC'));
        $start = $this->getStartDate();
        $end = $this->getEndDate();

        if (self::Capricorn === $this) {
            return $datePoint >= $start || $datePoint <= $end;
        }

        return $datePoint >= $start && $datePoint <= $end;
    }
}
