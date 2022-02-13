<?php

declare (strict_types=1);
namespace ECSPrefix20220213\Symplify\SymplifyKernel\Contract;

use ECSPrefix20220213\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \ECSPrefix20220213\Psr\Container\ContainerInterface;
    public function getContainer() : \ECSPrefix20220213\Psr\Container\ContainerInterface;
}
