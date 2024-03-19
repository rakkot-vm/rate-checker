<?php

declare(strict_types=1);

namespace App\Service\Provider\PrivateBank;

use App\Service\Provider\PrivateBank\Exception\ApiError;
use App\Service\Provider\PrivateBank\Response\ExchangeRatesResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PrivateBankHttpClient
{
    private const string BASE_URL = 'https://api.privatbank.ua';
    private const string ENDPOINT_GET_RATES = '/p24api/pubinfo?exchange&coursid=11';
    private const string API_ERROR_MESSAGE_TEMPLATE = 'API responded with error: "%s".';

    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function getExchangeRates(): ExchangeRatesResponse
    {
        $response = $this->send('GET', self::ENDPOINT_GET_RATES);

        return new ExchangeRatesResponse($response);
    }

    private function send(string $httpMethod, string $endpoint): array
    {
        $url = self::BASE_URL . $endpoint;

        try {
            $response = $this->client->request($httpMethod, $url);
        } catch (\Throwable $e) {
            throw new ApiError(
                sprintf(self::API_ERROR_MESSAGE_TEMPLATE, $e->getMessage())
            );
        }

        $responseBody = (string)$response->getContent();

        if ($response->getStatusCode() === 204 || empty($responseBody)) {
            return [];
        }

        $responseData = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($responseData)) {
            throw new ApiError(
                sprintf('Invalid response data.')
            );
        }

        return $responseData;
    }
}
