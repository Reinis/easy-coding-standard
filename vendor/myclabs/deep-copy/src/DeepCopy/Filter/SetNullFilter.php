<?php

namespace ECSPrefix20210803\DeepCopy\Filter;

use ECSPrefix20210803\DeepCopy\Reflection\ReflectionHelper;
/**
 * @final
 */
class SetNullFilter implements \ECSPrefix20210803\DeepCopy\Filter\Filter
{
    /**
     * Sets the object property to null.
     *
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = \ECSPrefix20210803\DeepCopy\Reflection\ReflectionHelper::getProperty($object, $property);
        $reflectionProperty->setAccessible(\true);
        $reflectionProperty->setValue($object, null);
    }
}
