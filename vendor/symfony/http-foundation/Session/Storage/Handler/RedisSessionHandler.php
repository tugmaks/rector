<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Component\HttpFoundation\Session\Storage\Handler;

use RectorPrefix20210607\Predis\Response\ErrorInterface;
use RectorPrefix20210607\Symfony\Component\Cache\Traits\RedisClusterProxy;
use RectorPrefix20210607\Symfony\Component\Cache\Traits\RedisProxy;
/**
 * Redis based session storage handler based on the Redis class
 * provided by the PHP redis extension.
 *
 * @author Dalibor Karlović <dalibor@flexolabs.io>
 */
class RedisSessionHandler extends \RectorPrefix20210607\Symfony\Component\HttpFoundation\Session\Storage\Handler\AbstractSessionHandler
{
    private $redis;
    /**
     * @var string Key prefix for shared environments
     */
    private $prefix;
    /**
     * @var int Time to live in seconds
     */
    private $ttl;
    /**
     * List of available options:
     *  * prefix: The prefix to use for the keys in order to avoid collision on the Redis server
     *  * ttl: The time to live in seconds.
     *
     * @param \Redis|\RedisArray|\RedisCluster|\Predis\ClientInterface|RedisProxy|RedisClusterProxy $redis
     *
     * @throws \InvalidArgumentException When unsupported client or options are passed
     */
    public function __construct($redis, array $options = [])
    {
        if (!$redis instanceof \Redis && !$redis instanceof \RedisArray && !$redis instanceof \RedisCluster && !$redis instanceof \RectorPrefix20210607\Predis\ClientInterface && !$redis instanceof \RectorPrefix20210607\Symfony\Component\Cache\Traits\RedisProxy && !$redis instanceof \RectorPrefix20210607\Symfony\Component\Cache\Traits\RedisClusterProxy) {
            throw new \InvalidArgumentException(\sprintf('"%s()" expects parameter 1 to be Redis, RedisArray, RedisCluster or Predis\\ClientInterface, "%s" given.', __METHOD__, \get_debug_type($redis)));
        }
        if ($diff = \array_diff(\array_keys($options), ['prefix', 'ttl'])) {
            throw new \InvalidArgumentException(\sprintf('The following options are not supported "%s".', \implode(', ', $diff)));
        }
        $this->redis = $redis;
        $this->prefix = $options['prefix'] ?? 'sf_s';
        $this->ttl = $options['ttl'] ?? null;
    }
    /**
     * {@inheritdoc}
     */
    protected function doRead(string $sessionId) : string
    {
        return $this->redis->get($this->prefix . $sessionId) ?: '';
    }
    /**
     * {@inheritdoc}
     */
    protected function doWrite(string $sessionId, string $data) : bool
    {
        $result = $this->redis->setEx($this->prefix . $sessionId, (int) ($this->ttl ?? \ini_get('session.gc_maxlifetime')), $data);
        return $result && !$result instanceof \RectorPrefix20210607\Predis\Response\ErrorInterface;
    }
    /**
     * {@inheritdoc}
     */
    protected function doDestroy(string $sessionId) : bool
    {
        static $unlink = \true;
        if ($unlink) {
            try {
                $unlink = \false !== $this->redis->unlink($this->prefix . $sessionId);
            } catch (\Throwable $e) {
                $unlink = \false;
            }
        }
        if (!$unlink) {
            $this->redis->del($this->prefix . $sessionId);
        }
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function close() : bool
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime) : bool
    {
        return \true;
    }
    /**
     * @return bool
     */
    public function updateTimestamp($sessionId, $data)
    {
        return (bool) $this->redis->expire($this->prefix . $sessionId, (int) ($this->ttl ?? \ini_get('session.gc_maxlifetime')));
    }
}
