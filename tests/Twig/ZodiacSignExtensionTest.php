<?php

namespace SebJean\ZodiacSignBundle\Tests\Twig;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\Twig\ZodiacSignExtension;
use SebJean\ZodiacSignBundle\ZodiacSignCalculator;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

#[CoversClass(ZodiacSignExtension::class)]
class ZodiacSignExtensionTest extends TestCase
{
    private ZodiacSignExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new ZodiacSignExtension();
    }

    public function testGetFilters(): void
    {
        $filters = $this->extension->getFilters();

        $this->assertIsArray($filters);
        $this->assertCount(1, $filters);
        $this->assertContainsOnlyInstancesOf(TwigFilter::class, $filters);

        $filter = $filters[0];
        $this->assertSame('zodiac_sign', $filter->getName());

        $callable = $filter->getCallable();
        $this->assertIsArray($callable);
        $this->assertSame(ZodiacSignCalculator::class, $callable[0]);
        $this->assertSame('calculateZodiacSign', $callable[1]);
    }

    public function testGetFunctions(): void
    {
        $functions = $this->extension->getFunctions();

        $this->assertIsArray($functions);
        $this->assertCount(1, $functions);
        $this->assertContainsOnlyInstancesOf(TwigFunction::class, $functions);

        $function = $functions[0];
        $this->assertSame('zodiac_sign', $function->getName());

        $callable = $function->getCallable();
        $this->assertIsArray($callable);
        $this->assertSame(ZodiacSignCalculator::class, $callable[0]);
        $this->assertSame('calculateZodiacSign', $callable[1]);
    }

    #[DataProvider('zodiacSignTwigProvider')]
    public function testTwigIntegration($date, string $expectedValue, string $expectedSymbol): void
    {
        $context = ['date' => $date];

        $twigFilterValue = $this->createTwigEnvironment('{{ date|zodiac_sign.value }}');
        $this->assertSame($expectedValue, $twigFilterValue->render('test.html.twig', $context));

        $twigFilterSymbol = $this->createTwigEnvironment('{{ date|zodiac_sign.symbol }}');
        $this->assertSame($expectedSymbol, $twigFilterSymbol->render('test.html.twig', $context));

        $twigFunctionValue = $this->createTwigEnvironment('{{ zodiac_sign(date).value }}');
        $this->assertSame($expectedValue, $twigFunctionValue->render('test.html.twig', $context));

        $twigFunctionSymbol = $this->createTwigEnvironment('{{ zodiac_sign(date).symbol }}');
        $this->assertSame($expectedSymbol, $twigFunctionSymbol->render('test.html.twig', $context));
    }

    public static function zodiacSignTwigProvider(): array
    {
        return [
            'Aries' => ['2000-03-21', 'aries', '♈'],
            'Taurus' => ['2000-04-20', 'taurus', '♉'],
            'Gemini' => ['2000-05-21', 'gemini', '♊'],
            'Cancer' => ['2000-06-21', 'cancer', '♋'],
            'Leo' => ['2000-08-01', 'leo', '♌'],
            'Virgo' => ['2000-08-23', 'virgo', '♍'],
            'Libra' => ['2000-09-23', 'libra', '♎'],
            'Scorpio' => ['2000-10-23', 'scorpio', '♏'],
            'Sagittarius' => ['2000-11-22', 'sagittarius', '♐'],
            'Capricorn' => ['2000-12-22', 'capricorn', '♑'],
            'Aquarius' => ['2000-01-20', 'aquarius', '♒'],
            'Pisces' => ['2000-02-19', 'pisces', '♓'],
            'Leo from timestamp' => [965088000, 'leo', '♌'],
            'Leo from DateTime' => [new \DateTime('2000-08-01'), 'leo', '♌'],
        ];
    }

    private function createTwigEnvironment(string $template): Environment
    {
        $loader = new ArrayLoader(['test.html.twig' => $template]);
        $twig = new Environment($loader);
        $twig->addExtension($this->extension);
        $twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load(string $class): ?object
            {
                if (ZodiacSignCalculator::class === $class) {
                    return new ZodiacSignCalculator();
                }

                return null;
            }
        });

        return $twig;
    }
}
