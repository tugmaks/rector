<?php

declare (strict_types=1);
namespace RectorPrefix20210607;

use Rector\Core\Bootstrap\RectorConfigsResolver;
use Rector\Core\Console\ConsoleApplication;
use Rector\Core\Console\Style\SymfonyStyleFactory;
use Rector\Core\DependencyInjection\RectorContainerFactory;
use Rector\Core\HttpKernel\RectorKernel;
use RectorPrefix20210607\Symplify\PackageBuilder\Console\ShellCode;
use RectorPrefix20210607\Symplify\PackageBuilder\Reflection\PrivatesCaller;
// @ intentionally: continue anyway
@\ini_set('memory_limit', '-1');
// Performance boost
\error_reporting(\E_ALL);
\ini_set('display_errors', 'stderr');
\gc_disable();
\define('__RECTOR_RUNNING__', \true);
// Require Composer autoload.php
$autoloadIncluder = new \RectorPrefix20210607\AutoloadIncluder();
$autoloadIncluder->includeDependencyOrRepositoryVendorAutoloadIfExists();
// load extracted PHPStan with its own preload.php
$extractedPhpstanAutoload = __DIR__ . '/../vendor/phpstan/phpstan-extracted/vendor/autoload.php';
if (\file_exists($extractedPhpstanAutoload)) {
    require_once $extractedPhpstanAutoload;
} elseif (\RectorPrefix20210607\should_include_preload()) {
    require_once __DIR__ . '/../preload.php';
}
require_once __DIR__ . '/../src/constants.php';
$autoloadIncluder->loadIfExistsAndNotLoadedYet(__DIR__ . '/../vendor/scoper-autoload.php');
$autoloadIncluder->autoloadProjectAutoloaderFile();
$autoloadIncluder->autoloadFromCommandLine();
$symfonyStyleFactory = new \Rector\Core\Console\Style\SymfonyStyleFactory(new \RectorPrefix20210607\Symplify\PackageBuilder\Reflection\PrivatesCaller());
$symfonyStyle = $symfonyStyleFactory->create();
$rectorConfigsResolver = new \Rector\Core\Bootstrap\RectorConfigsResolver();
try {
    $bootstrapConfigs = $rectorConfigsResolver->provide();
    $rectorContainerFactory = new \Rector\Core\DependencyInjection\RectorContainerFactory();
    $container = $rectorContainerFactory->createFromBootstrapConfigs($bootstrapConfigs);
} catch (\Throwable $throwable) {
    $symfonyStyle->error($throwable->getMessage());
    exit(\RectorPrefix20210607\Symplify\PackageBuilder\Console\ShellCode::ERROR);
}
/** @var ConsoleApplication $application */
$application = $container->get(\Rector\Core\Console\ConsoleApplication::class);
exit($application->run());
final class AutoloadIncluder
{
    /**
     * @var string[]
     */
    private $alreadyLoadedAutoloadFiles = [];
    public function includeDependencyOrRepositoryVendorAutoloadIfExists() : void
    {
        // Rector's vendor is already loaded
        if (\class_exists(\Rector\Core\HttpKernel\RectorKernel::class)) {
            return;
        }
        // in Rector develop repository
        $this->loadIfExistsAndNotLoadedYet(__DIR__ . '/../vendor/autoload.php');
    }
    /**
     * In case Rector is installed as vendor dependency,
     * this autoloads the project vendor/autoload.php, including Rector
     */
    public function autoloadProjectAutoloaderFile() : void
    {
        $this->loadIfExistsAndNotLoadedYet(__DIR__ . '/../../../autoload.php');
    }
    public function autoloadFromCommandLine() : void
    {
        $cliArgs = $_SERVER['argv'];
        $autoloadOptionPosition = \array_search('-a', $cliArgs, \true) ?: \array_search('--autoload-file', $cliArgs, \true);
        if (!$autoloadOptionPosition) {
            return;
        }
        $autoloadFileValuePosition = $autoloadOptionPosition + 1;
        $fileToAutoload = $cliArgs[$autoloadFileValuePosition] ?? null;
        if ($fileToAutoload === null) {
            return;
        }
        $this->loadIfExistsAndNotLoadedYet($fileToAutoload);
    }
    public function loadIfExistsAndNotLoadedYet(string $filePath) : void
    {
        if (!\file_exists($filePath)) {
            return;
        }
        if (\in_array($filePath, $this->alreadyLoadedAutoloadFiles, \true)) {
            return;
        }
        $this->alreadyLoadedAutoloadFiles[] = \realpath($filePath);
        require_once $filePath;
    }
}
\class_alias('RectorPrefix20210607\\AutoloadIncluder', 'AutoloadIncluder', \false);
// load local php-parser only in prefixed version or development repository
function should_include_preload() : bool
{
    if (\file_exists(__DIR__ . '/../vendor/scoper-autoload.php')) {
        return \true;
    }
    if (!\file_exists(\getcwd() . '/composer.json')) {
        return \false;
    }
    $composerJsonFileContent = \file_get_contents(\getcwd() . '/composer.json');
    return \strpos($composerJsonFileContent, '"name": "rector/rector"') !== \false;
}
