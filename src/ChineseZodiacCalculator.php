<?php

namespace SebJean\ZodiacSignBundle;

use SebJean\ZodiacSignBundle\Chinese\ChineseNewYearCalculator;
use SebJean\ZodiacSignBundle\Enum\ChineseZodiacSign;
use Symfony\Component\Clock\DatePoint;

final class ChineseZodiacCalculator
{
    /** @var array<string, ChineseZodiacSign> */
    private array $cache = [];

    public function calculateChineseZodiacSign(\DateTimeInterface|string|int|float|null $date = null): ChineseZodiacSign
    {
        $originalDatePoint = match (true) {
            null === $date => new DatePoint(),
            \is_int($date) || \is_float($date) => DatePoint::createFromTimestamp($date),
            \is_string($date) => new DatePoint($date),
            default => DatePoint::createFromInterface($date),
        };

        // Normalize to date-only (midnight UTC) to avoid timezone-induced day shifts
        $datePoint = DatePoint::createFromFormat('!Y-m-d', $originalDatePoint->format('Y-m-d'), new \DateTimeZone('UTC'));

        return $this->cache[$datePoint->format('Y-m-d')] ??= $this->resolve($datePoint);
    }

    private function resolve(DatePoint $datePoint): ChineseZodiacSign
    {
        $year = (int) $datePoint->format('Y');
        $cnyThisYear = ChineseNewYearCalculator::newYear($year);

        if ($datePoint >= $cnyThisYear) {
            return ChineseNewYearCalculator::signForYear($year);
        }

        return ChineseNewYearCalculator::signForYear($year - 1);
    }
}
