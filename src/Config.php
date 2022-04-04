<?php

namespace Przelewy24;

use InvalidArgumentException;

class Config
{
    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string|null
     */
    private $posId;

    /**
     * @var string
     */
    private $crc;

    /**
     * @var string
     */
    private $report;

    /**
     * @var bool
     */
    private $isLiveMode;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->set($parameters);
    }

    /**
     * @param array $parameters
     * @return \Przelewy24\Config
     */
    public function set(array $parameters): self
    {
        if (!$parameters['merchantId']) {
            throw new InvalidArgumentException('"merchantId" must be specified in the configuration parameters.');
        }

        if (!$parameters['crc']) {
            throw new InvalidArgumentException('"crc" must be specified in the configuration parameters.');
        }

        if (!$parameters['report']) {
            throw new InvalidArgumentException('"report" must be specified in the configuration parameters.');
        }

        $this->merchantId = $parameters['merchantId'];
        $this->posId = $parameters['posId'] ?? null;
        $this->crc = $parameters['crc'];
        $this->report = $parameters['report'];
        $this->isLiveMode = isset($parameters['live']) && (bool) $parameters['live'] === true;

        return $this;
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * @return int
     */
    public function getPosId(): int
    {
        return $this->posId ?? $this->getMerchantId();
    }

    /**
     * @return string
     */
    public function getCrc(): string
    {
        return $this->crc;
    }

    /**
     * @return string
     */
    public function getReport(): string
    {
        return $this->report;
    }

    /**
     * @return bool
     */
    public function isLiveMode(): bool
    {
        return $this->isLiveMode;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'merchantId' => $this->getMerchantId(),
            'posId' => $this->getPosId(),
            'crc' => $this->getCrc(),
            'report' => $this->getReport(),
        ];
    }
}
