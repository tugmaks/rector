<?php

declare (strict_types=1);
namespace Rector\ChangesReporting\Output;

use RectorPrefix20210607\Nette\Utils\Json;
use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\Core\Configuration\Configuration;
use Rector\Core\ValueObject\ProcessResult;
use RectorPrefix20210607\Symplify\SmartFileSystem\SmartFileSystem;
final class JsonOutputFormatter implements \Rector\ChangesReporting\Contract\Output\OutputFormatterInterface
{
    /**
     * @var string
     */
    public const NAME = 'json';
    /**
     * @var \Rector\Core\Configuration\Configuration
     */
    private $configuration;
    /**
     * @var \Symplify\SmartFileSystem\SmartFileSystem
     */
    private $smartFileSystem;
    /**
     * @var \Rector\ChangesReporting\Annotation\RectorsChangelogResolver
     */
    private $rectorsChangelogResolver;
    public function __construct(\Rector\Core\Configuration\Configuration $configuration, \RectorPrefix20210607\Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem, \Rector\ChangesReporting\Annotation\RectorsChangelogResolver $rectorsChangelogResolver)
    {
        $this->configuration = $configuration;
        $this->smartFileSystem = $smartFileSystem;
        $this->rectorsChangelogResolver = $rectorsChangelogResolver;
    }
    public function getName() : string
    {
        return self::NAME;
    }
    public function report(\Rector\Core\ValueObject\ProcessResult $processResult) : void
    {
        $errorsArray = ['meta' => ['config' => $this->configuration->getMainConfigFilePath()], 'totals' => ['changed_files' => \count($processResult->getFileDiffs()), 'removed_and_added_files_count' => $processResult->getRemovedAndAddedFilesCount(), 'removed_node_count' => $processResult->getRemovedNodeCount()]];
        $fileDiffs = $processResult->getFileDiffs();
        \ksort($fileDiffs);
        foreach ($fileDiffs as $fileDiff) {
            $relativeFilePath = $fileDiff->getRelativeFilePath();
            $appliedRectorsWithChangelog = $this->rectorsChangelogResolver->resolve($fileDiff->getRectorClasses());
            $errorsArray['file_diffs'][] = ['file' => $relativeFilePath, 'diff' => $fileDiff->getDiff(), 'applied_rectors' => $fileDiff->getRectorClasses(), 'applied_rectors_with_changelog' => $appliedRectorsWithChangelog];
            // for Rector CI
            $errorsArray['changed_files'][] = $relativeFilePath;
        }
        $errors = $processResult->getErrors();
        $errorsArray['totals']['errors'] = \count($errors);
        $errorsData = $this->createErrorsData($errors);
        if ($errorsData !== []) {
            $errorsArray['errors'] = $errorsData;
        }
        $json = \RectorPrefix20210607\Nette\Utils\Json::encode($errorsArray, \RectorPrefix20210607\Nette\Utils\Json::PRETTY);
        $outputFile = $this->configuration->getOutputFile();
        if ($outputFile !== null) {
            $this->smartFileSystem->dumpFile($outputFile, $json . \PHP_EOL);
        } else {
            echo $json . \PHP_EOL;
        }
    }
    /**
     * @param mixed[] $errors
     * @return mixed[]
     */
    private function createErrorsData(array $errors) : array
    {
        $errorsData = [];
        foreach ($errors as $error) {
            $errorData = ['message' => $error->getMessage(), 'file' => $error->getRelativeFilePath()];
            if ($error->getRectorClass()) {
                $errorData['caused_by'] = $error->getRectorClass();
            }
            if ($error->getLine() !== null) {
                $errorData['line'] = $error->getLine();
            }
            $errorsData[] = $errorData;
        }
        return $errorsData;
    }
}
