<?php

declare (strict_types=1);
namespace RectorPrefix20210607;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\CakePHP\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/{Rector,ValueObject,Contract}']);
};
