<?php

namespace RectorPrefix20210607;

if (\interface_exists('Tx_Extbase_Persistence_QOM_PropertyValueInterface')) {
    return;
}
interface Tx_Extbase_Persistence_QOM_PropertyValueInterface
{
}
\class_alias('Tx_Extbase_Persistence_QOM_PropertyValueInterface', 'Tx_Extbase_Persistence_QOM_PropertyValueInterface', \false);
