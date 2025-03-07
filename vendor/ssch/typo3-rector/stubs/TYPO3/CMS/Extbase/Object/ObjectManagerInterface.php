<?php

namespace RectorPrefix20210607\TYPO3\CMS\Extbase\Object;

if (\class_exists('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface')) {
    return;
}
interface ObjectManagerInterface
{
    /**
     * @param $objectName
     *
     * @return object
     */
    public function get($objectName);
}
