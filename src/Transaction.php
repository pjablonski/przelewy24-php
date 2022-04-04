<?php

namespace Przelewy24;

use Przelewy24\Api\Api;
use Przelewy24\Api\Request\ApiRequest;

class Transaction extends ApiRequest
{
    public const LANGUAGE_BG = 'ng';
    public const LANGUAGE_CS = 'cs';
    public const LANGUAGE_DE = 'de';
    public const LANGUAGE_EN = 'en';
    public const LANGUAGE_ES = 'es';
    public const LANGUAGE_FR = 'fr';
    public const LANGUAGE_HR = 'hr';
    public const LANGUAGE_HU = 'hu';
    public const LANGUAGE_IT = 'it';
    public const LANGUAGE_NL = 'nl';
    public const LANGUAGE_PL = 'pl';
    public const LANGUAGE_PT = 'pt';
    public const LANGUAGE_SE = 'se';
    public const LANGUAGE_SK = 'sk';

    public const SUPPORTED_LANGUAGES = [
        self::LANGUAGE_BG,
        self::LANGUAGE_CS,
        self::LANGUAGE_DE,
        self::LANGUAGE_EN,
        self::LANGUAGE_ES,
        self::LANGUAGE_FR,
        self::LANGUAGE_HR,
        self::LANGUAGE_HU,
        self::LANGUAGE_IT,
        self::LANGUAGE_NL,
        self::LANGUAGE_PL,
        self::LANGUAGE_PT,
        self::LANGUAGE_SE,
        self::LANGUAGE_SK,
    ];

    public const CURRENCY_PLN = 'PLN';

    public const CHANNEL_CARD = 1;
    public const CHANNEL_WIRE = 2;
    public const CHANNEL_TRADITIONAL_WIRE = 4;
    public const CHANNEL_ALL_24_7 = 16;
    public const CHANNEL_PREPAYMENT = 32;
    public const CHANNEL_PAY_BY_LINK = 64;

    public const ENCODING_ISO_8859_2 = 'ISO-8859-2';
    public const ENCODING_UTF_8 = 'UTF-8';
    public const ENCODING_WINDOWS_1250 = 'Windows-1250';

    /**
     * @var array
     */
    protected $signatureAttributes = [
        'sessionId',
        'merchantId',
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
        'crc',
    ];

    /**
     * @var array|\Przelewy24\TransactionProduct[]
     */
    private $products = [];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = array_merge($parameters, [
            'currency' => strtoupper($parameters['currency']),
            'country' => strtoupper($parameters['country'] ?? self::LANGUAGE_PL),
            'language' => strtoupper(in_array($lang = $parameters['language'], self::SUPPORTED_LANGUAGES) ? $lang : self::LANGUAGE_PL)
        ]);
    }

    /**
     * @param array $product
     * @return \Przelewy24\Transaction
     */
    public function addProduct(array $product): self
    {
        $this->products[] = new TransactionProduct($product);

        return $this;
    }
}
