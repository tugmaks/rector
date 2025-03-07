<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Component\DependencyInjection\Attribute;

/**
 * An attribute to tell how a base type should be autoconfigured.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @Attribute
 */
class Autoconfigure
{
    /**
     * @var mixed[]|null
     */
    public $tags;
    /**
     * @var mixed[]|null
     */
    public $calls;
    /**
     * @var mixed[]|null
     */
    public $bind;
    /**
     * @var bool|string|null
     */
    public $lazy = null;
    /**
     * @var bool|null
     */
    public $public;
    /**
     * @var bool|null
     */
    public $shared;
    /**
     * @var bool|null
     */
    public $autowire;
    /**
     * @var mixed[]|null
     */
    public $properties;
    /**
     * @var mixed[]|string|null
     */
    public $configurator = null;
    /**
     * @param bool|string|null $lazy
     * @param mixed[]|string|null $configurator
     * @param mixed[]|null $tags
     * @param mixed[]|null $calls
     * @param mixed[]|null $bind
     * @param bool|null $public
     * @param bool|null $shared
     * @param bool|null $autowire
     * @param mixed[]|null $properties
     */
    public function __construct($tags = null, $calls = null, $bind = null, $lazy = null, $public = null, $shared = null, $autowire = null, $properties = null, $configurator = null)
    {
        $this->tags = $tags;
        $this->calls = $calls;
        $this->bind = $bind;
        $this->lazy = $lazy;
        $this->public = $public;
        $this->shared = $shared;
        $this->autowire = $autowire;
        $this->properties = $properties;
        $this->configurator = $configurator;
    }
}
