<?php

namespace Przelewy24\Api\Request;

use Przelewy24\Config;

class ApiRequest implements SignedApiRequest
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $signatureAttributes = [];

    /**
     * @var array
     */
    protected $configAttributes = [
        'merchantId',
        'posId',
        'crc',
        'report',
    ];

    /**
     * @param \Przelewy24\Config $config
     * @return \Przelewy24\Api\Request\SignedApiRequest
     */
    public function setConfig(Config $config): SignedApiRequest
    {
        $this->parameters = array_merge($this->parameters, array_intersect_key($config->toArray(), array_flip($this->configAttributes)));

        return $this;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        $parameters = [];

        foreach ($this->parameters as $parameter => $value) {
            $parameters[$parameter] = $value;
        }

        $parameters['sign'] = $this->signature();

        return $parameters;
    }

    /**
     * @return string
     */
    public function signature(): string
    {
        $parameters = [];

        foreach ($this->signatureAttributes as $param) {
            $parameters[$param] = $this->parameters[$param];
        }

        return hash('sha384', json_encode($parameters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->parameters[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }
}
