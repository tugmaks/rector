<?php

namespace RectorPrefix20210607\TYPO3\CMS\Extbase\Service;

if (\class_exists('TYPO3\\CMS\\Extbase\\Service\\EnvironmentService')) {
    return;
}
class EnvironmentService
{
    /**
     * @return string
     */
    public function isEnvironmentInCliMode()
    {
        return 'foo';
    }
}
