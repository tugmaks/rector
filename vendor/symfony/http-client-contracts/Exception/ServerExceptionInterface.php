<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210607\Symfony\Contracts\HttpClient\Exception;

/**
 * When a 5xx response is returned.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
interface ServerExceptionInterface extends \RectorPrefix20210607\Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
{
}
