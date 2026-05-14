<?php

namespace SebJean\ZodiacSignBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SebJean\ZodiacSignBundle\ChineseZodiacCalculator;
use SebJean\ZodiacSignBundle\Twig\ZodiacSignExtension;
use SebJean\ZodiacSignBundle\ZodiacSignBundle;
use SebJean\ZodiacSignBundle\ZodiacSignCalculator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

#[CoversClass(ZodiacSignBundle::class)]
class ZodiacSignBundleTest extends TestCase
{
    public function testLoadExtensionLoadsServices(): void
    {
        $container = new ContainerBuilder();
        $bundle = new ZodiacSignBundle();

        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/config'));
        $instanceof = [];
        $configurator = new ContainerConfigurator($container, $loader, $instanceof, 'services.php', 'services.php');

        $bundle->loadExtension([], $configurator, $container);

        $this->assertTrue($container->hasDefinition(ZodiacSignCalculator::class));
        $this->assertTrue($container->hasDefinition(ChineseZodiacCalculator::class));
        $this->assertTrue($container->hasDefinition(ZodiacSignExtension::class));
    }
}
