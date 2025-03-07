<?php

declare (strict_types=1);
namespace Rector\PSR4\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PHPStan\Reflection\Constant\RuntimeConstantReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Configuration\Option;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use RectorPrefix20210607\Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;
use RectorPrefix20210607\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class FullyQualifyStmtsAnalyzer
{
    /**
     * @var \Symplify\PackageBuilder\Parameter\ParameterProvider
     */
    private $parameterProvider;
    /**
     * @var \Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser
     */
    private $simpleCallableNodeTraverser;
    /**
     * @var \Rector\NodeNameResolver\NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var \PHPStan\Reflection\ReflectionProvider
     */
    private $reflectionProvider;
    public function __construct(\RectorPrefix20210607\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider, \RectorPrefix20210607\Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser $simpleCallableNodeTraverser, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \PHPStan\Reflection\ReflectionProvider $reflectionProvider)
    {
        $this->parameterProvider = $parameterProvider;
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->reflectionProvider = $reflectionProvider;
    }
    /**
     * @param Stmt[] $nodes
     */
    public function process(array $nodes) : void
    {
        // no need to
        if ($this->parameterProvider->provideBoolParameter(\Rector\Core\Configuration\Option::AUTO_IMPORT_NAMES)) {
            return;
        }
        // FQNize all class names
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($nodes, function (\PhpParser\Node $node) : ?FullyQualified {
            if (!$node instanceof \PhpParser\Node\Name) {
                return null;
            }
            $fullyQualifiedName = $this->nodeNameResolver->getName($node);
            if (\in_array($fullyQualifiedName, ['self', 'parent', 'static'], \true)) {
                return null;
            }
            if ($this->isNativeConstant($node)) {
                return null;
            }
            return new \PhpParser\Node\Name\FullyQualified($fullyQualifiedName);
        });
    }
    private function isNativeConstant(\PhpParser\Node\Name $name) : bool
    {
        $parent = $name->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parent instanceof \PhpParser\Node\Expr\ConstFetch) {
            return \false;
        }
        $scope = $name->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if (!$this->reflectionProvider->hasConstant($name, $scope)) {
            return \false;
        }
        $constantReflection = $this->reflectionProvider->getConstant($name, $scope);
        return $constantReflection instanceof \PHPStan\Reflection\Constant\RuntimeConstantReflection;
    }
}
