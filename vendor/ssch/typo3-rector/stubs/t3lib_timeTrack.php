<?php

namespace RectorPrefix20210607;

if (\class_exists('t3lib_timeTrack')) {
    return;
}
class t3lib_timeTrack
{
}
\class_alias('t3lib_timeTrack', 't3lib_timeTrack', \false);
