<?php

use SebJean\ZodiacSignBundle\ChineseZodiacCalculator;
use SebJean\ZodiacSignBundle\Twig\ZodiacSignExtension;
use SebJean\ZodiacSignBundle\ZodiacSignCalculator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ZodiacSignCalculator::class)
            ->tag('twig.runtime')

        ->set(ChineseZodiacCalculator::class)
            ->tag('twig.runtime')

        ->set(ZodiacSignExtension::class)
            ->tag('twig.extension')
    ;
};
