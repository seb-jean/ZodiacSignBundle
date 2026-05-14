<?php

namespace SebJean\ZodiacSignBundle;

final class DateRange implements \Stringable
{
    public function __construct(
        private readonly \DateTimeImmutable $start,
        private readonly \DateTimeImmutable $end,
        private readonly bool $excludeEnd = false,
    ) {
    }

    public function contains(\DateTimeInterface $date): bool
    {
        if ($this->excludeEnd) {
            return $date >= $this->start && $date < $this->end;
        }

        return $date >= $this->start && $date <= $this->end;
    }

    public function __toString(): string
    {
        return $this->start->format('Y-m-d').' - '.$this->end->format('Y-m-d');
    }
}
