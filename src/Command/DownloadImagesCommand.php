<?php

declare(strict_types=1);

namespace App\Command;

use App\Nasa\Epic\EpicImageClient;
use App\Nasa\Epic\EpicImageStorage;
use App\Nasa\Exception\MissingInformationException;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

/**
 * This command downloads images from the NASA EPIC API.
 * The images to be downloaded can be limited by providing an imagery type and a date.
 */
#[AsCommand(
    name: 'nasa:epic:download-images',
    description: "'Downloads images from the NASA EPIC (Earth Polychromatic Imaging Camera) API"
)]
class DownloadImagesCommand extends Command
{
    private const IMAGERY_TYPES = ['natural', 'enhanced', 'aerosol', 'cloud'];

    private const IMAGE_FORMATS = ['png', 'jpg'];

    /**
     * @param EpicImageClient $imageClient
     * @param EpicImageStorage $imageStorage
     * @param Filesystem $filesystem
     */
    public function __construct(
        private readonly EpicImageClient $imageClient,
        private readonly EpicImageStorage $imageStorage,
        private readonly Filesystem $filesystem
    ) {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->addArgument('image-folder', InputArgument::REQUIRED, 'The root folder for the downloaded images')
            ->addArgument(
                'date',
                InputArgument::OPTIONAL,
                'The date for which images are downloaded. If none given, the last available day is used'
            )
            ->addOption(
                'imagery-type',
                't',
                InputOption::VALUE_OPTIONAL,
                'The imagery type of the NASA images. Possible values are ' . implode(',', self::IMAGERY_TYPES),
                'natural'
            )
            ->addOption(
                'image-format',
                'f',
                InputOption::VALUE_OPTIONAL,
                'The image format. Possible values are ' . implode(',', self::IMAGE_FORMATS),
                'png'
            );

        $this->setHelp('This command allows you to download and store images from the NASA EPIC API');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $imageFolder = $input->getArgument('image-folder');
        $imageDate = $input->getArgument('date');
        $imageryType = $input->getOption('imagery-type');
        $imageFormat = $input->getOption('image-format');

        if ($imageDate !== null) {
            try {
                $imageDate = new DateTimeImmutable($imageDate);
            } catch (Exception) {
                $output->writeln('The given date was invalid, please provide in form YYYY-MM-DD.');

                return Command::INVALID;
            }
        }

        if (!in_array($imageryType, self::IMAGERY_TYPES)) {
            $output->writeln(
                sprintf(
                    "Wrong imagery type '%s' given. Possible values are %s.",
                    $imageryType,
                    implode(', ', self::IMAGERY_TYPES)
                )
            );

            return Command::INVALID;
        }

        if (!in_array($imageFormat, self::IMAGE_FORMATS)) {
            $output->writeln(
                sprintf(
                    "Wrong image format '%s' given. Possible values are %s.",
                    $imageFormat,
                    implode(', ', self::IMAGE_FORMATS)
                )
            );

            return Command::INVALID;
        }

        try {
            $imageCollection = $this->imageClient->get($imageDate, $imageryType);
        } catch (Throwable $e) {
            $output->writeln(
                sprintf(
                    'The images could not be retrieved from the NASA EPIC API due to the following error: %s',
                    $e->getMessage()
                )
            );

            return Command::FAILURE;
        }

        $output->writeln(sprintf('%d images were found.', count($imageCollection)));

        if (!$this->filesystem->exists($imageFolder)) {
            try {
                $this->filesystem->mkdir($imageFolder);
            } catch (IOException $e) {
                $output->writeln(
                    sprintf("The given folder '%s' for storing images could not be created.", $e->getPath())
                );

                return Command::INVALID;
            }
        }

        foreach ($imageCollection as $image) {
            try {
                $this->imageStorage->save($image, $imageFolder, $imageryType, $imageFormat);
            } catch (MissingInformationException $e) {
                $output->writeln(
                    sprintf(
                        'For image %s, the %s is missing.',
                        $image->getIdentifier(),
                        $e->getMissingInformation()
                    )
                );
                continue;
            } catch (IOException $e) {
                $output->writeln(
                    sprintf(
                        "The image could not be stored in the folder '%s'. The following error occured: %s",
                        $e->getPath(),
                        $e->getMessage()
                    )
                );

                return Command::FAILURE;
            }
        }

        $output->writeln(
            sprintf('All %d images were successfully downloaded and stored.', count($imageCollection))
        );

        return Command::SUCCESS;
    }
}
