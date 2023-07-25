<?php

declare (strict_types=1);
namespace ECSPrefix202307;

use ECSPrefix202307\Symplify\EasyCI\Config\EasyCIConfig;
return static function (EasyCIConfig $easyCIConfig) : void {
    $easyCIConfig->typesToSkip([\Symplify\CodingStandard\TokenRunner\DocBlock\MalformWorker\InlineVariableDocBlockMalformWorker::class, \Symplify\CodingStandard\TokenRunner\DocBlock\MalformWorker\MissingVarNameMalformWorker::class, \Symplify\CodingStandard\TokenRunner\DocBlock\MalformWorker\ParamNameReferenceMalformWorker::class]);
};
