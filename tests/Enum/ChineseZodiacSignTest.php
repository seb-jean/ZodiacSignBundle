<?php

namespace SebJean\ZodiacSignBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\Enum\ChineseZodiacSign;

class ChineseZodiacSignTest extends TestCase
{
    public function testAllTwelveCasesExist(): void
    {
        $cases = ChineseZodiacSign::cases();

        $this->assertCount(12, $cases);
    }

    public function testCaseValues(): void
    {
        $this->assertSame('rat', ChineseZodiacSign::Rat->value);
        $this->assertSame('ox', ChineseZodiacSign::Ox->value);
        $this->assertSame('tiger', ChineseZodiacSign::Tiger->value);
        $this->assertSame('rabbit', ChineseZodiacSign::Rabbit->value);
        $this->assertSame('dragon', ChineseZodiacSign::Dragon->value);
        $this->assertSame('snake', ChineseZodiacSign::Snake->value);
        $this->assertSame('horse', ChineseZodiacSign::Horse->value);
        $this->assertSame('goat', ChineseZodiacSign::Goat->value);
        $this->assertSame('monkey', ChineseZodiacSign::Monkey->value);
        $this->assertSame('rooster', ChineseZodiacSign::Rooster->value);
        $this->assertSame('dog', ChineseZodiacSign::Dog->value);
        $this->assertSame('pig', ChineseZodiacSign::Pig->value);
    }

    public function testGetSymbol(): void
    {
        $this->assertSame('🐀', ChineseZodiacSign::Rat->getSymbol());
        $this->assertSame('🐂', ChineseZodiacSign::Ox->getSymbol());
        $this->assertSame('🐅', ChineseZodiacSign::Tiger->getSymbol());
        $this->assertSame('🐇', ChineseZodiacSign::Rabbit->getSymbol());
        $this->assertSame('🐉', ChineseZodiacSign::Dragon->getSymbol());
        $this->assertSame('🐍', ChineseZodiacSign::Snake->getSymbol());
        $this->assertSame('🐎', ChineseZodiacSign::Horse->getSymbol());
        $this->assertSame('🐐', ChineseZodiacSign::Goat->getSymbol());
        $this->assertSame('🐒', ChineseZodiacSign::Monkey->getSymbol());
        $this->assertSame('🐓', ChineseZodiacSign::Rooster->getSymbol());
        $this->assertSame('🐕', ChineseZodiacSign::Dog->getSymbol());
        $this->assertSame('🐖', ChineseZodiacSign::Pig->getSymbol());
    }
}
