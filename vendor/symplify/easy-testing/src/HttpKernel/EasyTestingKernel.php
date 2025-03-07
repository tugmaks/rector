<?php

declare (strict_types=1);
namespace RectorPrefix20210607\Symplify\EasyTesting\HttpKernel;

use RectorPrefix20210607\Symfony\Component\Config\Loader\LoaderInterface;
use RectorPrefix20210607\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \RectorPrefix20210607\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    public function registerContainerConfiguration(\RectorPrefix20210607\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
    }
}
