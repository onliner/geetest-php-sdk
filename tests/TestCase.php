<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Invokes object method, even if it is private or protected.
     *
     * @param mixed $object the object.
     * @param string $method the method name.
     * @param array<mixed> $args the method arguments.
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    protected function invoke($object, string $method, array $args = [])
    {
        $classReflection = new \ReflectionClass(get_class($object));
        $methodReflection = $classReflection->getMethod($method);
        $methodReflection->setAccessible(true);
        $result = $methodReflection->invokeArgs($object, $args);
        $methodReflection->setAccessible(false);

        return $result;
    }
}
