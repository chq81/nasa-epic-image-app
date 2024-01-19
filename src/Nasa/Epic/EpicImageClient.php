<?php

declare(strict_types=1);

namespace App\Nasa\Epic;

use App\Model\EpicNasaImage;
use App\Nasa\Exception\NasaClientFailedException;
use App\Nasa\Exception\NasaClientResponseEmptyException;
use DateTimeImmutable;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * The EPIC image client provides functionality to retrieve images from the NASA EPIC API.
 */
final readonly class EpicImageClient
{
    /**
     * @param string $nasaEpicApiRootUrl
     * @param string $nasaApiKey
     * @param HttpClientInterface $httpClient
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private string $nasaEpicApiRootUrl,
        private string $nasaApiKey,
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * Retrieves images from the NASA EPIC API for a given date and imagery type.
     *
     * @return EpicNasaImage[]
     * @throws TransportExceptionInterface
     */
    public function get(?DateTimeImmutable $date, string $imageryType = 'natural'): array
    {
        $imageUrl = $this->nasaEpicApiRootUrl . '/' . $imageryType . '/date/' . $date?->format('Y-m-d');
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            $imageUrl,
            [
                'query' => [
                    'api_key' => $this->nasaApiKey,
                ],
            ]
        );

        try {
            $content = $response->getContent();
        } catch (HttpExceptionInterface|TransportExceptionInterface) {
            $content = null;
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new NasaClientFailedException($content, $response->getStatusCode());
        }

        if ($content === null) {
            throw new NasaClientResponseEmptyException('The content of the response is empty.');
        }

        return $this->serializer->deserialize($content, 'array<App\Model\EpicNasaImage>', 'json');
    }
}
