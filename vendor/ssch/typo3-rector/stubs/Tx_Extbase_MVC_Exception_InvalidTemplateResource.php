<?php

namespace RectorPrefix20210607;

if (\class_exists('Tx_Extbase_MVC_Exception_InvalidTemplateResource')) {
    return;
}
class Tx_Extbase_MVC_Exception_InvalidTemplateResource
{
}
\class_alias('Tx_Extbase_MVC_Exception_InvalidTemplateResource', 'Tx_Extbase_MVC_Exception_InvalidTemplateResource', \false);
