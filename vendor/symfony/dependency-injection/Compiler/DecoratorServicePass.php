<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Component\DependencyInjection\Compiler;

use RectorPrefix20210607\Symfony\Component\DependencyInjection\Alias;
use RectorPrefix20210607\Symfony\Component\DependencyInjection\ContainerBuilder;
use RectorPrefix20210607\Symfony\Component\DependencyInjection\ContainerInterface;
use RectorPrefix20210607\Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use RectorPrefix20210607\Symfony\Component\DependencyInjection\Reference;
/**
 * Overwrites a service but keeps the overridden one.
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Diego Saint Esteben <diego@saintesteben.me>
 */
class DecoratorServicePass extends \RectorPrefix20210607\Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass
{
    private $innerId = '.inner';
    public function __construct(?string $innerId = '.inner')
    {
        if (0 < \func_num_args()) {
            trigger_deprecation('symfony/dependency-injection', '5.3', 'Configuring "%s" is deprecated.', __CLASS__);
        }
        $this->innerId = $innerId;
    }
    public function process(\RectorPrefix20210607\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $definitions = new \SplPriorityQueue();
        $order = \PHP_INT_MAX;
        foreach ($container->getDefinitions() as $id => $definition) {
            if (!($decorated = $definition->getDecoratedService())) {
                continue;
            }
            $definitions->insert([$id, $definition], [$decorated[2], --$order]);
        }
        $decoratingDefinitions = [];
        foreach ($definitions as [$id, $definition]) {
            $decoratedService = $definition->getDecoratedService();
            [$inner, $renamedId] = $decoratedService;
            $invalidBehavior = $decoratedService[3] ?? \RectorPrefix20210607\Symfony\Component\DependencyInjection\ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
            $definition->setDecoratedService(null);
            if (!$renamedId) {
                $renamedId = $id . '.inner';
            }
            $this->currentId = $renamedId;
            $this->processValue($definition);
            $definition->innerServiceId = $renamedId;
            $definition->decorationOnInvalid = $invalidBehavior;
            // we create a new alias/service for the service we are replacing
            // to be able to reference it in the new one
            if ($container->hasAlias($inner)) {
                $alias = $container->getAlias($inner);
                $public = $alias->isPublic();
                $private = $alias->isPrivate();
                $container->setAlias($renamedId, new \RectorPrefix20210607\Symfony\Component\DependencyInjection\Alias((string) $alias, \false));
            } elseif ($container->hasDefinition($inner)) {
                $decoratedDefinition = $container->getDefinition($inner);
                $public = $decoratedDefinition->isPublic();
                $private = $decoratedDefinition->isPrivate();
                $decoratedDefinition->setPublic(\false);
                $container->setDefinition($renamedId, $decoratedDefinition);
                $decoratingDefinitions[$inner] = $decoratedDefinition;
            } elseif (\RectorPrefix20210607\Symfony\Component\DependencyInjection\ContainerInterface::IGNORE_ON_INVALID_REFERENCE === $invalidBehavior) {
                $container->removeDefinition($id);
                continue;
            } elseif (\RectorPrefix20210607\Symfony\Component\DependencyInjection\ContainerInterface::NULL_ON_INVALID_REFERENCE === $invalidBehavior) {
                $public = $definition->isPublic();
                $private = $definition->isPrivate();
            } else {
                throw new \RectorPrefix20210607\Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException($inner, $id);
            }
            if (isset($decoratingDefinitions[$inner])) {
                $decoratingDefinition = $decoratingDefinitions[$inner];
                $decoratingTags = $decoratingDefinition->getTags();
                $resetTags = [];
                // container.service_locator and container.service_subscriber have special logic and they must not be transferred out to decorators
                foreach (['container.service_locator', 'container.service_subscriber'] as $containerTag) {
                    if (isset($decoratingTags[$containerTag])) {
                        $resetTags[$containerTag] = $decoratingTags[$containerTag];
                        unset($decoratingTags[$containerTag]);
                    }
                }
                $definition->setTags(\array_merge($decoratingTags, $definition->getTags()));
                $decoratingDefinition->setTags($resetTags);
                $decoratingDefinitions[$inner] = $definition;
            }
            $container->setAlias($inner, $id)->setPublic($public);
        }
    }
    protected function processValue($value, bool $isRoot = \false)
    {
        if ($value instanceof \RectorPrefix20210607\Symfony\Component\DependencyInjection\Reference && $this->innerId === (string) $value) {
            return new \RectorPrefix20210607\Symfony\Component\DependencyInjection\Reference($this->currentId, $value->getInvalidBehavior());
        }
        return parent::processValue($value, $isRoot);
    }
}
