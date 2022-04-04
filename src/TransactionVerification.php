<?php

namespace Przelewy24;

use Przelewy24\Api\Request\ApiRequest;

class TransactionVerification extends ApiRequest
{
    /**
     * @var array
     */
    protected $signatureAttributes = [
        'sessionId',
        'orderId',
        'amount',
        'currency',
        'crc',
    ];

    /**
     * @var array
     */
    protected $configAttributes = [
        'merchantId',
        'posId',
        'crc'
    ];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = array_merge($parameters, [
            'currency' => strtoupper($parameters['currency']),
        ]);
    }
}
