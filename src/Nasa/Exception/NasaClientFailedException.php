<?php

declare(strict_types=1);

namespace App\Nasa\Exception;

use RuntimeException;

/**
 * This exception is thrown when the nasa client does not return a successful response.
 */
class NasaClientFailedException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param string|null $content
     * @param int $statusCode
     */
    public function __construct(
        private readonly ?string $content,
        private readonly int $statusCode
    ) {
        parent::__construct($this->content ?? 'The content is empty', $this->statusCode);
    }
}
