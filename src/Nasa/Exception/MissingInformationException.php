<?php

declare(strict_types=1);

namespace App\Nasa\Exception;

use RuntimeException;

/**
 * This exception is thrown when relevant image information from the NASA API client is missing.
 */
class MissingInformationException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param string $missingInformation
     */
    public function __construct(
        private readonly string $missingInformation
    ) {
        parent::__construct('The image is missing vital information');
    }

    /**
     * Retrieves the missing information.
     */
    public function getMissingInformation(): string
    {
        return $this->missingInformation;
    }
}
