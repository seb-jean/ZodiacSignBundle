<?php

namespace SebJean\ZodiacSignBundle\Enum;

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
