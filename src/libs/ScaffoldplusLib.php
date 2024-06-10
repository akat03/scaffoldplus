<?php

namespace Akat03\Scaffoldplus\libs;


class ScaffoldplusLib
{
    static function getLangDir()
    {
        if (is_dir(base_path('lang'))) {
            return base_path('lang');
        } else {
            return resource_path('lang');
        }
    }
}
