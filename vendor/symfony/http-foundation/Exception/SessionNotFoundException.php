<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Component\HttpFoundation\Exception;

/**
 * Raised when a session does not exists. This happens in the following cases:
 * - the session is not enabled
 * - attempt to read a session outside a request context (ie. cli script).
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class SessionNotFoundException extends \LogicException implements \RectorPrefix20210607\Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface
{
    public function __construct($message = 'There is currently no session available.', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
