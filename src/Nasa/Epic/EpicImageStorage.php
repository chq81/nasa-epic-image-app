<?php

declare(strict_types=1);

namespace App\Nasa\Epic;

use App\Model\EpicNasaImage;
use App\Nasa\Exception\MissingInformationException;
use DateTimeImmutable;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The EPIC image storage provides functionality to store images to a given folder.
 */
final readonly class EpicImageStorage
{
    /**
     * @param string $nasaEpicArchiveRootUrl
     * @param Filesystem $filesystem
     */
    public function __construct(
        private string $nasaEpicArchiveRootUrl,
        private Filesystem $filesystem
    ) {
    }

    /**
     * Stores the given image to the provided folder, each folder specifying the date of the image.
     */
    public function save(
        EpicNasaImage $image,
        string $imageFolder,
        string $imageryType,
        string $imageFormat = 'png'
    ): void {
        if ($image->getImage() === null) {
            throw new MissingInformationException('name');
        }

        if (!$image->getDate() instanceof DateTimeImmutable) {
            throw new MissingInformationException('date');
        }

        $imageName = $image->getImage() . '.' . $imageFormat;
        $imageDate = $image->getDate();

        $imageStorageFolder = "{$imageFolder}/" . $imageDate->format('Y-m-d');

        if (!$this->filesystem->exists($imageStorageFolder)) {
            $this->filesystem->mkdir($imageStorageFolder);
        }

        $year = $imageDate->format('Y');
        $month = $imageDate->format('m');
        $day = $imageDate->format('d');

        $imageArchivePath = $this->nasaEpicArchiveRootUrl . "/{$imageryType}/{$year}/{$month}/{$day}/{$imageFormat}/{$imageName}";

        $storagePath = "{$imageStorageFolder}/{$imageName}";
        $this->filesystem->copy($imageArchivePath, $storagePath);
    }
}
