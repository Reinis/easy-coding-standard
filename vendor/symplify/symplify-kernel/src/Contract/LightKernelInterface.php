<?php

declare (strict_types=1);
namespace ECSPrefix20211102\Symplify\SymplifyKernel\Contract;

use ECSPrefix20211102\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \ECSPrefix20211102\Psr\Container\ContainerInterface;
    public function getContainer() : \ECSPrefix20211102\Psr\Container\ContainerInterface;
}