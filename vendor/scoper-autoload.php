<?php

// scoper-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('AutoloadIncluder', false) && !interface_exists('AutoloadIncluder', false) && !trait_exists('AutoloadIncluder', false)) {
    spl_autoload_call('ECSPrefix20220531\AutoloadIncluder');
}
if (!class_exists('ComposerAutoloaderInit4c7c0f77371e0eaebdd709b631ea7c5d', false) && !interface_exists('ComposerAutoloaderInit4c7c0f77371e0eaebdd709b631ea7c5d', false) && !trait_exists('ComposerAutoloaderInit4c7c0f77371e0eaebdd709b631ea7c5d', false)) {
    spl_autoload_call('ECSPrefix20220531\ComposerAutoloaderInit4c7c0f77371e0eaebdd709b631ea7c5d');
}
if (!class_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false) && !interface_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false) && !trait_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false)) {
    spl_autoload_call('ECSPrefix20220531\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator');
}
if (!class_exists('Attribute', false) && !interface_exists('Attribute', false) && !trait_exists('Attribute', false)) {
    spl_autoload_call('ECSPrefix20220531\Attribute');
}
if (!class_exists('PhpToken', false) && !interface_exists('PhpToken', false) && !trait_exists('PhpToken', false)) {
    spl_autoload_call('ECSPrefix20220531\PhpToken');
}
if (!class_exists('Stringable', false) && !interface_exists('Stringable', false) && !trait_exists('Stringable', false)) {
    spl_autoload_call('ECSPrefix20220531\Stringable');
}
if (!class_exists('UnhandledMatchError', false) && !interface_exists('UnhandledMatchError', false) && !trait_exists('UnhandledMatchError', false)) {
    spl_autoload_call('ECSPrefix20220531\UnhandledMatchError');
}
if (!class_exists('ValueError', false) && !interface_exists('ValueError', false) && !trait_exists('ValueError', false)) {
    spl_autoload_call('ECSPrefix20220531\ValueError');
}
if (!class_exists('ReturnTypeWillChange', false) && !interface_exists('ReturnTypeWillChange', false) && !trait_exists('ReturnTypeWillChange', false)) {
    spl_autoload_call('ECSPrefix20220531\ReturnTypeWillChange');
}
if (!class_exists('Symplify\SmartFileSystem\SmartFileInfo', false) && !interface_exists('Symplify\SmartFileSystem\SmartFileInfo', false) && !trait_exists('Symplify\SmartFileSystem\SmartFileInfo', false)) {
    spl_autoload_call('ECSPrefix20220531\Symplify\SmartFileSystem\SmartFileInfo');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('composerRequire4c7c0f77371e0eaebdd709b631ea7c5d')) {
    function composerRequire4c7c0f77371e0eaebdd709b631ea7c5d() {
        return \ECSPrefix20220531\composerRequire4c7c0f77371e0eaebdd709b631ea7c5d(...func_get_args());
    }
}
if (!function_exists('sample')) {
    function sample() {
        return \ECSPrefix20220531\sample(...func_get_args());
    }
}
if (!function_exists('foo')) {
    function foo() {
        return \ECSPrefix20220531\foo(...func_get_args());
    }
}
if (!function_exists('bar')) {
    function bar() {
        return \ECSPrefix20220531\bar(...func_get_args());
    }
}
if (!function_exists('baz')) {
    function baz() {
        return \ECSPrefix20220531\baz(...func_get_args());
    }
}
if (!function_exists('xyz')) {
    function xyz() {
        return \ECSPrefix20220531\xyz(...func_get_args());
    }
}
if (!function_exists('array_is_list')) {
    function array_is_list() {
        return \ECSPrefix20220531\array_is_list(...func_get_args());
    }
}
if (!function_exists('uv_signal_init')) {
    function uv_signal_init() {
        return \ECSPrefix20220531\uv_signal_init(...func_get_args());
    }
}
if (!function_exists('uv_signal_start')) {
    function uv_signal_start() {
        return \ECSPrefix20220531\uv_signal_start(...func_get_args());
    }
}
if (!function_exists('uv_poll_init_socket')) {
    function uv_poll_init_socket() {
        return \ECSPrefix20220531\uv_poll_init_socket(...func_get_args());
    }
}
if (!function_exists('setproctitle')) {
    function setproctitle() {
        return \ECSPrefix20220531\setproctitle(...func_get_args());
    }
}
if (!function_exists('enum_exists')) {
    function enum_exists() {
        return \ECSPrefix20220531\enum_exists(...func_get_args());
    }
}

return $loader;
