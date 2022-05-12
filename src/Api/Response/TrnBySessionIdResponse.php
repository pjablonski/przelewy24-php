<?php

namespace Przelewy24\Api\Response;

class TrnBySessionIdResponse extends ApiResponse
{
    const STATUS_NO_PAYMENT = 0;

    const STATUS_ADVANCE_PAYMENT = 1;

    const STATUS_PAYMENT_MADE = 2;

    const STATUS_PAYMENT_RETURNED = 3;

    /** @var int */
    public $orderId;

    /** @var string */
    public $sessionId;

    /** @var int */
    public $status;

    /** @var int */
    public $amount;

    /** @var string */
    public $currency;

    /** @var string */
    public $date;

    /** @var string */
    public $dateOfTransaction;

    /** @var string */
    public $clientEmail;

    /** @var string */
    public $accountMD5;

    /** @var int */
    public $paymentMethod;

    /** @var string */
    public $description;

    /** @var string */
    public $clientName;

    /** @var string */
    public $clientAddress;

    /** @var string */
    public $clientCity;

    /** @var string */
    public $clientPostcode;

    /** @var int */
    public $batchId;

    /** @var string */
    public $fee;

    /** @var string */
    public $statement;

    public function __set($name, $value)
    {
        throw new \Exception("Can't set property: " . __CLASS__ . "->$name");
    }
}
