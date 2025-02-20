<?php

declare (strict_types=1);
namespace Rector\Naming\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver;
use Rector\Naming\ExpectedNameResolver\MatchPropertyTypeExpectedNameResolver;
use Rector\Naming\PropertyRenamer\MatchTypePropertyRenamer;
use Rector\Naming\PropertyRenamer\PropertyFetchRenamer;
use Rector\Naming\ValueObject\PropertyRename;
use Rector\Naming\ValueObjectFactory\PropertyRenameFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\RenamePropertyToMatchTypeRectorTest
 */
final class RenamePropertyToMatchTypeRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var bool
     */
    private $hasChanged = \false;
    /**
     * @var \Rector\Naming\PropertyRenamer\MatchTypePropertyRenamer
     */
    private $matchTypePropertyRenamer;
    /**
     * @var \Rector\Naming\ValueObjectFactory\PropertyRenameFactory
     */
    private $propertyRenameFactory;
    /**
     * @var \Rector\Naming\ExpectedNameResolver\MatchPropertyTypeExpectedNameResolver
     */
    private $matchPropertyTypeExpectedNameResolver;
    /**
     * @var \Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver
     */
    private $matchParamTypeExpectedNameResolver;
    /**
     * @var \Rector\Naming\PropertyRenamer\PropertyFetchRenamer
     */
    private $propertyFetchRenamer;
    public function __construct(\Rector\Naming\PropertyRenamer\MatchTypePropertyRenamer $matchTypePropertyRenamer, \Rector\Naming\ValueObjectFactory\PropertyRenameFactory $propertyRenameFactory, \Rector\Naming\ExpectedNameResolver\MatchPropertyTypeExpectedNameResolver $matchPropertyTypeExpectedNameResolver, \Rector\Naming\ExpectedNameResolver\MatchParamTypeExpectedNameResolver $matchParamTypeExpectedNameResolver, \Rector\Naming\PropertyRenamer\PropertyFetchRenamer $propertyFetchRenamer)
    {
        $this->matchTypePropertyRenamer = $matchTypePropertyRenamer;
        $this->propertyRenameFactory = $propertyRenameFactory;
        $this->matchPropertyTypeExpectedNameResolver = $matchPropertyTypeExpectedNameResolver;
        $this->matchParamTypeExpectedNameResolver = $matchParamTypeExpectedNameResolver;
        $this->propertyFetchRenamer = $propertyFetchRenamer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Rename property and method param to match its type', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var EntityManager
     */
    private $eventManager;

    public function __construct(EntityManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class, \PhpParser\Node\Stmt\Interface_::class];
    }
    /**
     * @param Class_|Interface_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $this->refactorClassProperties($node);
        $this->renamePropertyPromotion($node);
        if (!$this->hasChanged) {
            return null;
        }
        return $node;
    }
    private function refactorClassProperties(\PhpParser\Node\Stmt\ClassLike $classLike) : void
    {
        foreach ($classLike->getProperties() as $property) {
            $expectedPropertyName = $this->matchPropertyTypeExpectedNameResolver->resolve($property);
            if ($expectedPropertyName === null) {
                continue;
            }
            $propertyRename = $this->propertyRenameFactory->createFromExpectedName($property, $expectedPropertyName);
            if (!$propertyRename instanceof \Rector\Naming\ValueObject\PropertyRename) {
                continue;
            }
            $renameProperty = $this->matchTypePropertyRenamer->rename($propertyRename);
            if (!$renameProperty instanceof \PhpParser\Node\Stmt\Property) {
                continue;
            }
            $this->hasChanged = \true;
        }
    }
    private function renamePropertyPromotion(\PhpParser\Node\Stmt\ClassLike $classLike) : void
    {
        if (!$this->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::PROPERTY_PROMOTION)) {
            return;
        }
        $constructClassMethod = $classLike->getMethod(\Rector\Core\ValueObject\MethodName::CONSTRUCT);
        if (!$constructClassMethod instanceof \PhpParser\Node\Stmt\ClassMethod) {
            return;
        }
        $desiredPropertyNames = [];
        foreach ($constructClassMethod->params as $key => $param) {
            if ($param->flags === 0) {
                continue;
            }
            // promoted property
            $desiredPropertyName = $this->matchParamTypeExpectedNameResolver->resolve($param);
            if ($desiredPropertyName === null) {
                continue;
            }
            if (\in_array($desiredPropertyName, $desiredPropertyNames, \true)) {
                return;
            }
            $desiredPropertyNames[$key] = $desiredPropertyName;
        }
        $this->renameParamVarName($classLike, $constructClassMethod->params, $desiredPropertyNames);
    }
    /**
     * @param Param[] $params
     * @param string[] $desiredPropertyNames
     */
    private function renameParamVarName(\PhpParser\Node\Stmt\ClassLike $classLike, array $params, array $desiredPropertyNames) : void
    {
        $keys = \array_keys($desiredPropertyNames);
        foreach ($params as $key => $param) {
            if (\in_array($key, $keys, \true)) {
                $currentName = $this->getName($param);
                $desiredPropertyName = $desiredPropertyNames[$key];
                $this->propertyFetchRenamer->renamePropertyFetchesInClass($classLike, $currentName, $desiredPropertyName);
                $param->var->name = $desiredPropertyName;
            }
        }
    }
}
