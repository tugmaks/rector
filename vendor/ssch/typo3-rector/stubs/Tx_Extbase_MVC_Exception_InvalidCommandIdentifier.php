<?php

namespace RectorPrefix20210607;

if (\class_exists('Tx_Extbase_MVC_Exception_InvalidCommandIdentifier')) {
    return;
}
class Tx_Extbase_MVC_Exception_InvalidCommandIdentifier
{
}
\class_alias('Tx_Extbase_MVC_Exception_InvalidCommandIdentifier', 'Tx_Extbase_MVC_Exception_InvalidCommandIdentifier', \false);
