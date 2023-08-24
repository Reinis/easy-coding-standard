<?php

declare (strict_types=1);
namespace ECSPrefix202308;

use ECSPrefix202308\Symplify\EasyParallel\Contract\SerializableInterface;
use ECSPrefix202308\Symplify\EasyCI\Config\EasyCIConfig;
return static function (EasyCIConfig $easyCIConfig) : void {
    $easyCIConfig->typesToSkip([SerializableInterface::class]);
};
