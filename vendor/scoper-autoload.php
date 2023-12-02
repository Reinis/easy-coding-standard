<?php

// scoper-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/autoload.php';

// Exposed functions. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#exposing-functions
if (!function_exists('trigger_deprecation')) {
    function trigger_deprecation() {
        return \ECSPrefix202312\trigger_deprecation(...func_get_args());
    }
}

return $loader;
