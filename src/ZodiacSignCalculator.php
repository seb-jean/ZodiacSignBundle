<?php

namespace SebJean\ZodiacSignBundle;

use SebJean\ZodiacSignBundle\Enum\ZodiacSign;
use Symfony\Component\Clock\DatePoint;

final class ZodiacSignCalculator
{
    /** @var array<string, ZodiacSign> */
    private array $cache = [];

    public function calculateZodiacSign(\DateTimeInterface|string|int|float|null $date = null): ZodiacSign
    {
        $originalDatePoint = match (true) {
            null === $date => new DatePoint(),
            \is_int($date) || \is_float($date) => DatePoint::createFromTimestamp($date),
            \is_string($date) => new DatePoint($date),
            default => DatePoint::createFromInterface($date),
        };

        return $this->cache[$originalDatePoint->format('m-d')] ??= $this->resolve($originalDatePoint);
    }

    private function resolve(DatePoint $datePoint): ZodiacSign
    {
        foreach (ZodiacSign::cases() as $zodiacSign) {
            if ($zodiacSign->contains($datePoint)) {
                return $zodiacSign;
            }
        }

        throw new \InvalidArgumentException(\sprintf('Unable to determine zodiac sign for date "%s".', $datePoint->format(\DateTimeInterface::RFC3339)));
    }
}
