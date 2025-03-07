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

use RectorPrefix20210607\Symfony\Component\HttpFoundation\Response;
/**
 * Allows to create a response for a request.
 *
 * Call setResponse() to set the response that will be returned for the
 * current request. The propagation of this event is stopped as soon as a
 * response is set.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RequestEvent extends \RectorPrefix20210607\Symfony\Component\HttpKernel\Event\KernelEvent
{
    private $response;
    /**
     * Returns the response object.
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * Sets a response and stops event propagation.
     */
    public function setResponse(\RectorPrefix20210607\Symfony\Component\HttpFoundation\Response $response)
    {
        $this->response = $response;
        $this->stopPropagation();
    }
    /**
     * Returns whether a response was set.
     *
     * @return bool Whether a response was set
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }
}
