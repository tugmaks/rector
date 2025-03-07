<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Component\HttpKernel\Event;

use RectorPrefix20210607\Symfony\Component\HttpFoundation\Request;
use RectorPrefix20210607\Symfony\Component\HttpKernel\HttpKernelInterface;
use RectorPrefix20210607\Symfony\Contracts\EventDispatcher\Event;
/**
 * Base class for events thrown in the HttpKernel component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class KernelEvent extends \RectorPrefix20210607\Symfony\Contracts\EventDispatcher\Event
{
    private $kernel;
    private $request;
    private $requestType;
    /**
     * @param int $requestType The request type the kernel is currently processing; one of
     *                         HttpKernelInterface::MAIN_REQUEST or HttpKernelInterface::SUB_REQUEST
     */
    public function __construct(\RectorPrefix20210607\Symfony\Component\HttpKernel\HttpKernelInterface $kernel, \RectorPrefix20210607\Symfony\Component\HttpFoundation\Request $request, ?int $requestType)
    {
        $this->kernel = $kernel;
        $this->request = $request;
        $this->requestType = $requestType;
    }
    /**
     * Returns the kernel in which this event was thrown.
     *
     * @return HttpKernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }
    /**
     * Returns the request the kernel is currently processing.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * Returns the request type the kernel is currently processing.
     *
     * @return int One of HttpKernelInterface::MAIN_REQUEST and
     *             HttpKernelInterface::SUB_REQUEST
     */
    public function getRequestType()
    {
        return $this->requestType;
    }
    /**
     * Checks if this is the main request.
     */
    public function isMainRequest() : bool
    {
        return \RectorPrefix20210607\Symfony\Component\HttpKernel\HttpKernelInterface::MAIN_REQUEST === $this->requestType;
    }
    /**
     * Checks if this is a master request.
     *
     * @return bool True if the request is a master request
     *
     * @deprecated since symfony/http-kernel 5.3, use isMainRequest() instead
     */
    public function isMasterRequest()
    {
        trigger_deprecation('symfony/http-kernel', '5.3', '"%s()" is deprecated, use "isMainRequest()" instead.', __METHOD__);
        return $this->isMainRequest();
    }
}
