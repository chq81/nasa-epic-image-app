<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use Exception;

/**
 * This model represents an image from the NASA EPIC API.
 */
final class EpicNasaImage
{
    /**
     * The identifier of the image.
     */
    private string $identifier;

    /**
     * The name of the image. Can be empty.
     */
    private ?string $image = null;

    /**
     * The date of the image. Can be empty.
     */
    private ?string $date = null;

    /**
     * Returns the identifier of the image.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier of the image.
     *
     * @param string $identifier
     * @return void
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * Returns the name of the image.
     *
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sets the name of the image.
     *
     * @param string $image
     * @return void
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * Returns the date of the image as a DateTimeImmutable object.
     * In case of a conversion exception, the method returns null.
     *
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        if ($this->date === null) {
            return null;
        }

        try {
            return new DateTimeImmutable($this->date);
        } catch (Exception) {
            return null;
        }
    }

    /**
     * Sets the date of the image.
     *
     * @param string $date
     * @return void
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}
