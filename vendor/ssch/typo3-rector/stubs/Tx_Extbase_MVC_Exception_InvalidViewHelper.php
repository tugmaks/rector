<?php

namespace RectorPrefix20210607;

if (\class_exists('Tx_Extbase_MVC_Exception_InvalidViewHelper')) {
    return;
}
class Tx_Extbase_MVC_Exception_InvalidViewHelper
{
}
\class_alias('Tx_Extbase_MVC_Exception_InvalidViewHelper', 'Tx_Extbase_MVC_Exception_InvalidViewHelper', \false);
