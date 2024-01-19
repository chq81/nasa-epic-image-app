<?php

declare(strict_types=1);

namespace App\Nasa\Epic\Model;

/**
 * This interfaces holds the image format definitions.
 */
interface ImageFormatInterface
{
    final public const IMAGE_FORMAT_PNG = 'png';

    final public const IMAGE_FORMAT_JPG = 'jpg';

    final public const IMAGE_FORMATS = [self::IMAGE_FORMAT_PNG, self::IMAGE_FORMAT_JPG];
}
