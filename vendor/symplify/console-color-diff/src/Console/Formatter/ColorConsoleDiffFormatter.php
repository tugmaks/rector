<?php

declare (strict_types=1);
namespace RectorPrefix20210607\Symplify\ConsoleColorDiff\Console\Formatter;

use RectorPrefix20210607\Nette\Utils\Strings;
use RectorPrefix20210607\Symfony\Component\Console\Formatter\OutputFormatter;
/**
 * Most is copy-pasted from https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/src/Differ/DiffConsoleFormatter.php
 * to be used as standalone class, without need to require whole package.
 *
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * @see \Symplify\ConsoleColorDiff\Tests\Console\Formatter\ColorConsoleDiffFormatterTest
 */
final class ColorConsoleDiffFormatter
{
    /**
     * @var string
     * @see https://regex101.com/r/ovLMDF/1
     */
    private const PLUS_START_REGEX = '#^(\\+.*)#';
    /**
     * @var string
     * @see https://regex101.com/r/xwywpa/1
     */
    private const MINUT_START_REGEX = '#^(\\-.*)#';
    /**
     * @var string
     * @see https://regex101.com/r/CMlwa8/1
     */
    private const AT_START_REGEX = '#^(@.*)#';
    /**
     * @var string
     * @see https://regex101.com/r/qduj2O/1
     */
    private const NEWLINES_REGEX = "#\n\r|\n#";
    /**
     * @var string
     */
    private $template;
    public function __construct()
    {
        $this->template = \sprintf('<comment>    ---------- begin diff ----------</comment>%s%%s%s<comment>    ----------- end diff -----------</comment>' . \PHP_EOL, \PHP_EOL, \PHP_EOL);
    }
    public function format(string $diff) : string
    {
        return $this->formatWithTemplate($diff, $this->template);
    }
    private function formatWithTemplate(string $diff, string $template) : string
    {
        $escapedDiff = \RectorPrefix20210607\Symfony\Component\Console\Formatter\OutputFormatter::escape(\rtrim($diff));
        $escapedDiffLines = \RectorPrefix20210607\Nette\Utils\Strings::split($escapedDiff, self::NEWLINES_REGEX);
        $coloredLines = \array_map(function (string $string) : string {
            $string = $this->makePlusLinesGreen($string);
            $string = $this->makeMinusLinesRed($string);
            $string = $this->makeAtNoteCyan($string);
            if ($string === ' ') {
                return '';
            }
            return $string;
        }, $escapedDiffLines);
        return \sprintf($template, \implode(\PHP_EOL, $coloredLines));
    }
    private function makePlusLinesGreen(string $string) : string
    {
        return \RectorPrefix20210607\Nette\Utils\Strings::replace($string, self::PLUS_START_REGEX, '<fg=green>$1</fg=green>');
    }
    private function makeMinusLinesRed(string $string) : string
    {
        return \RectorPrefix20210607\Nette\Utils\Strings::replace($string, self::MINUT_START_REGEX, '<fg=red>$1</fg=red>');
    }
    private function makeAtNoteCyan(string $string) : string
    {
        return \RectorPrefix20210607\Nette\Utils\Strings::replace($string, self::AT_START_REGEX, '<fg=cyan>$1</fg=cyan>');
    }
}
