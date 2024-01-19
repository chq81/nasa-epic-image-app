<?php

declare(strict_types=1);

namespace App\Nasa\Exception;

use RuntimeException;

/**
 * This exception is thrown when the response from nasa client is empty.
 */
class NasaClientResponseEmptyException extends RuntimeException implements ExceptionInterface
{
}
