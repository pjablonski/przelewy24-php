<?php

namespace Przelewy24;

class TransactionStatusNotification
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters = $data;
    }

    /**
     * @return string
     */
    public function sessionId(): string
    {
        return $this->parameters['sessionId'];
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return $this->parameters['amount'];
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->parameters['currency'];
    }

    /**
     * @return int
     */
    public function orderId(): int
    {
        return $this->parameters['orderId'];
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->parameters['method'];
    }

    /**
     * @return string
     */
    public function statement(): string
    {
        return $this->parameters['statement'];
    }

    /**
     * @return string
     */
    public function sign(): string
    {
        return $this->parameters['sign'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->parameters;
    }
}
