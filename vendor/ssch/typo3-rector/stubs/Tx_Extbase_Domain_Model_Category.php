<?php

namespace RectorPrefix20210607;

if (\class_exists('Tx_Extbase_Domain_Model_Category')) {
    return;
}
class Tx_Extbase_Domain_Model_Category
{
}
\class_alias('Tx_Extbase_Domain_Model_Category', 'Tx_Extbase_Domain_Model_Category', \false);
