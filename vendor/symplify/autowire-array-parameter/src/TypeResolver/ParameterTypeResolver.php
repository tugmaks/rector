<?php

declare (strict_types=1);
namespace RectorPrefix20210607\Symplify\AutowireArrayParameter\TypeResolver;

use RectorPrefix20210607\Nette\Utils\Reflection;
use ReflectionMethod;
use RectorPrefix20210607\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver;
final class ParameterTypeResolver
{
    /**
     * @var ParamTypeDocBlockResolver
     */
    private $paramTypeDocBlockResolver;
    /**
     * @var array<string, string>
     */
    private $resolvedParameterTypesCached = [];
    public function __construct(\RectorPrefix20210607\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver $paramTypeDocBlockResolver)
    {
        $this->paramTypeDocBlockResolver = $paramTypeDocBlockResolver;
    }
    public function resolveParameterType(string $parameterName, \ReflectionMethod $reflectionMethod) : ?string
    {
        $docComment = $reflectionMethod->getDocComment();
        if ($docComment === \false) {
            return null;
        }
        $declaringReflectionClass = $reflectionMethod->getDeclaringClass();
        $uniqueKey = $parameterName . $declaringReflectionClass->getName() . $reflectionMethod->getName();
        if (isset($this->resolvedParameterTypesCached[$uniqueKey])) {
            return $this->resolvedParameterTypesCached[$uniqueKey];
        }
        $resolvedType = $this->paramTypeDocBlockResolver->resolve($docComment, $parameterName);
        if ($resolvedType === null) {
            return null;
        }
        // not a class|interface type
        if (\ctype_lower($resolvedType[0])) {
            return null;
        }
        $resolvedClass = \RectorPrefix20210607\Nette\Utils\Reflection::expandClassName($resolvedType, $declaringReflectionClass);
        $this->resolvedParameterTypesCached[$uniqueKey] = $resolvedClass;
        return $resolvedClass;
    }
}
