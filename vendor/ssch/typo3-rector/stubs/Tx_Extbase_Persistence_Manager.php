<?php

namespace RectorPrefix20210607;

if (\class_exists('Tx_Extbase_Persistence_Manager')) {
    return;
}
class Tx_Extbase_Persistence_Manager
{
}
\class_alias('Tx_Extbase_Persistence_Manager', 'Tx_Extbase_Persistence_Manager', \false);
