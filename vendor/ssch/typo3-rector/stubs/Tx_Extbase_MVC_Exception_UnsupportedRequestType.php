<?php

namespace RectorPrefix20210607;

if (\class_exists('Tx_Extbase_MVC_Exception_UnsupportedRequestType')) {
    return;
}
class Tx_Extbase_MVC_Exception_UnsupportedRequestType
{
}
\class_alias('Tx_Extbase_MVC_Exception_UnsupportedRequestType', 'Tx_Extbase_MVC_Exception_UnsupportedRequestType', \false);
