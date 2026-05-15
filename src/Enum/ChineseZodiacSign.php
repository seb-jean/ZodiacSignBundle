<?php

namespace SebJean\ZodiacSignBundle\Enum;

use SebJean\ZodiacSignBundle\Chinese\ChineseNewYearCalculator;
use SebJean\ZodiacSignBundle\DateRange;
use Symfony\Component\Clock\DatePoint;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ChineseZodiacSign: string implements TranslatableInterface
{
    case Rat = 'rat';
    case Ox = 'ox';
    case Tiger = 'tiger';
    case Rabbit = 'rabbit';
    case Dragon = 'dragon';
    case Snake = 'snake';
    case Horse = 'horse';
    case Goat = 'goat';
    case Monkey = 'monkey';
    case Rooster = 'rooster';
    case Dog = 'dog';
    case Pig = 'pig';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('chinese_zodiac_sign.'.$this->value, domain: 'ZodiacSignBundle', locale: $locale);
    }

    public function getPeriod(?int $year = null): DateRange
    {
        $year ??= (int) (new DatePoint())->format('Y');

        // Find the year in which this sign's cycle starts
        $startYear = $year;
        if (ChineseNewYearCalculator::signForYear($year) !== $this) {
            $startYear = $year - 1;
            if (ChineseNewYearCalculator::signForYear($startYear) !== $this) {
                throw new \InvalidArgumentException(\sprintf(
                    'The Chinese zodiac sign "%s" does not appear in year %d.',
                    $this->value,
                    $year,
                ));
            }
        }

        $start = ChineseNewYearCalculator::newYear($startYear);
        $end = ChineseNewYearCalculator::newYear($startYear + 1);

        return new DateRange($start, $end, excludeEnd: true);
    }

    public function getSymbol(): string
    {
        return match ($this) {
            self::Rat => '🐀',
            self::Ox => '🐂',
            self::Tiger => '🐅',
            self::Rabbit => '🐇',
            self::Dragon => '🐉',
            self::Snake => '🐍',
            self::Horse => '🐎',
            self::Goat => '🐐',
            self::Monkey => '🐒',
            self::Rooster => '🐓',
            self::Dog => '🐕',
            self::Pig => '🐖',
        };
    }
}
