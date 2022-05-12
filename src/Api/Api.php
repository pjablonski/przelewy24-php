<?php

namespace Przelewy24\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Przelewy24\Api\Request\SignedApiRequest;
use Przelewy24\Api\Response\PaymentMethodsResponse;
use Przelewy24\Api\Response\RegisterTransactionResponse;
use Przelewy24\Api\Response\TestConnectionResponse;
use Przelewy24\Api\Response\TrnBySessionIdResponse;
use Przelewy24\Api\Response\VerifyTransactionResponse;
use Przelewy24\Config;
use Psr\Http\Message\ResponseInterface;

class Api
{
    const PREFIX = '/api/v1';

    const URL_LIVE = 'https://secure.przelewy24.pl/';
    const URL_SANDBOX = 'https://sandbox.przelewy24.pl/';

    const ENDPOINT_TEST = 'testAccess/';
    const ENDPOINT_REGISTER = 'transaction/register/';
    const ENDPOINT_VERIFY = 'transaction/verify/';
    const ENDPOINT_PAYMENT_METHODS = 'payment/methods/';
    const ENDPOINT_PAYMENT_GATEWAY = 'trnRequest';
    const ENDPOINT_TRN_BY_SESSID = 'transaction/by/sessionId/';

    /**
     * @var \Przelewy24\Config
     */
    private $config;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * @param \Przelewy24\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Przelewy24\Api\Response\TestConnectionResponse
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function testConnection(): TestConnectionResponse
    {
        $response = $this->request(self::ENDPOINT_TEST, [], 'get');

        return new TestConnectionResponse($response);
    }

    /**
     * @param \Przelewy24\Api\Request\SignedApiRequest $apiRequest
     * @return \Przelewy24\Api\Response\RegisterTransactionResponse
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function registerTransaction(SignedApiRequest $apiRequest): RegisterTransactionResponse
    {
        $apiRequest->setConfig($this->config);

        $response = new RegisterTransactionResponse(
            $this->request(self::ENDPOINT_REGISTER, $apiRequest->parameters())
        );

        $response->setGatewayUrl(
            $this->getApiUrl() . self::ENDPOINT_PAYMENT_GATEWAY
        );

        return $response;
    }

    /**
     * @param \Przelewy24\Api\Request\SignedApiRequest $apiRequest
     * @return \Przelewy24\Api\Response\VerifyTransactionResponse
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function verifyTransaction(SignedApiRequest $apiRequest): VerifyTransactionResponse
    {
        $apiRequest->setConfig($this->config);

        $response = new VerifyTransactionResponse(
            $this->request(self::ENDPOINT_VERIFY, $apiRequest->parameters(), 'put')
        );

        return $response;
    }

    /**
     * @return PaymentMethodsResponse
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function paymentMethods(string $lang): PaymentMethodsResponse
    {
        $response = $this->request(self::ENDPOINT_PAYMENT_METHODS . $lang, [], 'get');

        return new PaymentMethodsResponse($response);
    }

    /**
     * @param string $sessionId
     * @return TrnBySessionIdResponse
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function trnBySessionId(string $sessionId): TrnBySessionIdResponse
    {
        $response = $this->request(self::ENDPOINT_TRN_BY_SESSID . $sessionId, [], 'get');

        return new TrnBySessionIdResponse($response);
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    private function client(string $url = null): ClientInterface
    {
        if (!$this->client) {
            $this->client = new Client([
                'auth' => [$this->config->getMerchantId(), $this->config->getReport()],
                'base_uri' => $url ?? $this->getApiUrl() . self::PREFIX . '/',
                'http_errors' => false
            ]);
        }

        return $this->client;
    }

    /**
     * //
     *
     * @param string $endpoint
     * @param array $parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function request(string $endpoint, array $parameters, string $method = 'post'): ResponseInterface
    {
        return $this->client()->request($method, $endpoint, [
            'form_params' => $parameters,
        ]);
    }

    /**
     * @return string
     */
    private function getApiUrl(): string
    {
        return $this->config->isLiveMode() ? self::URL_LIVE : self::URL_SANDBOX;
    }
}
