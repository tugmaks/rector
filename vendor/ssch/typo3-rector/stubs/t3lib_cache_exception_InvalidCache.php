<?php

namespace RectorPrefix20210607;

if (\class_exists('t3lib_cache_exception_InvalidCache')) {
    return;
}
class t3lib_cache_exception_InvalidCache
{
}
\class_alias('t3lib_cache_exception_InvalidCache', 't3lib_cache_exception_InvalidCache', \false);
