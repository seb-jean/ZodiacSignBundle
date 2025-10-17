<?php

namespace SebJean\ZodiacSignBundle\Twig;

use SebJean\ZodiacSignBundle\ZodiacSignCalculator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class ZodiacSignExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('zodiac_sign', [ZodiacSignCalculator::class, 'calculateZodiacSign']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('zodiac_sign', [ZodiacSignCalculator::class, 'calculateZodiacSign']),
        ];
    }
}
