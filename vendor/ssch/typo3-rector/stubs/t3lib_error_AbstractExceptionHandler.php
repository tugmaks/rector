<?php

namespace RectorPrefix20210607;

if (\class_exists('t3lib_error_AbstractExceptionHandler')) {
    return;
}
class t3lib_error_AbstractExceptionHandler
{
}
\class_alias('t3lib_error_AbstractExceptionHandler', 't3lib_error_AbstractExceptionHandler', \false);
