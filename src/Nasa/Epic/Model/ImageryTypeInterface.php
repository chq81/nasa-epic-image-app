<?php

declare(strict_types=1);

namespace App\Nasa\Epic\Model;

/**
 * This interfaces holds the imagery type definitions.
 */
interface ImageryTypeInterface
{
    final public const IMAGERY_TYPE_NATURAL = 'natural';

    final public const IMAGERY_TYPE_ENHANCED = 'enhanced';

    final public const IMAGERY_TYPE_AEROSOL = 'aerosol';

    final public const IMAGERY_TYPE_CLOUD = 'cloud';

    final public const IMAGERY_TYPES = [
        self::IMAGERY_TYPE_NATURAL,
        self::IMAGERY_TYPE_ENHANCED,
        self::IMAGERY_TYPE_AEROSOL,
        self::IMAGERY_TYPE_CLOUD,
    ];
}
