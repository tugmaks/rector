<?php

declare (strict_types=1);
namespace Ssch\TYPO3Rector\FileProcessor\Composer\Rector;

use BadMethodCallException;
use Rector\Composer\Contract\Rector\ComposerRectorInterface;
use RectorPrefix20210607\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @changelog https://docs.typo3.org/m/typo3/guide-installation/master/en-us/MigrateToComposer/MigrationSteps.html
 * @see \Ssch\TYPO3Rector\Tests\FileProcessor\Composer\Rector\RemoveCmsPackageDirFromExtraComposerRector\RemoveCmsPackageDirFromExtraComposerRectorTest
 */
final class RemoveCmsPackageDirFromExtraComposerRector implements \Rector\Composer\Contract\Rector\ComposerRectorInterface
{
    /**
     * @var string
     */
    private const TYPO3_CMS = 'typo3/cms';
    public function refactor(\RectorPrefix20210607\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        $extra = $composerJson->getExtra();
        if (!isset($extra[self::TYPO3_CMS])) {
            return;
        }
        if (!isset($extra[self::TYPO3_CMS]['cms-package-dir'])) {
            return;
        }
        unset($extra[self::TYPO3_CMS]['cms-package-dir']);
        $composerJson->setExtra($extra);
    }
    public function configure(array $configuration) : void
    {
        throw new \BadMethodCallException('Not allowed. No configuration option available');
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change package name in `composer.json`', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
{
    "extra": {
        "typo3/cms": {
            "cms-package-dir": "{$vendor-dir}/typo3/cms"
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
{
    "extra": {
        "typo3/cms": {
        }
    }
}
CODE_SAMPLE
, ['not_allowed' => 'not_available'])]);
    }
}
