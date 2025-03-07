<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Component\HttpKernel\ControllerMetadata;

/**
 * Builds {@see ArgumentMetadata} objects based on the given Controller.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class ArgumentMetadataFactory implements \RectorPrefix20210607\Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createArgumentMetadata($controller) : array
    {
        $arguments = [];
        if (\is_array($controller)) {
            $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (\is_object($controller) && !$controller instanceof \Closure) {
            $reflection = (new \ReflectionObject($controller))->getMethod('__invoke');
        } else {
            $reflection = new \ReflectionFunction($controller);
        }
        foreach ($reflection->getParameters() as $param) {
            $attributes = [];
            if (\PHP_VERSION_ID >= 80000) {
                foreach ($param->getAttributes() as $reflectionAttribute) {
                    if (\class_exists($reflectionAttribute->getName())) {
                        $attributes[] = $reflectionAttribute->newInstance();
                    }
                }
            }
            $arguments[] = new \RectorPrefix20210607\Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata($param->getName(), $this->getType($param, $reflection), $param->isVariadic(), $param->isDefaultValueAvailable(), $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null, $param->allowsNull(), $attributes);
        }
        return $arguments;
    }
    /**
     * Returns an associated type to the given parameter if available.
     */
    private function getType(\ReflectionParameter $parameter, \ReflectionFunctionAbstract $function) : ?string
    {
        if (!($type = $parameter->getType())) {
            return null;
        }
        $name = $type instanceof \ReflectionNamedType ? $type->getName() : (string) $type;
        if ($function instanceof \ReflectionMethod) {
            $lcName = \strtolower($name);
            switch ($lcName) {
                case 'self':
                    return $function->getDeclaringClass()->name;
                case 'parent':
                    return ($parent = $function->getDeclaringClass()->getParentClass()) ? $parent->name : null;
            }
        }
        return $name;
    }
}
