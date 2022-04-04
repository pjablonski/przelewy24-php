<?php

namespace Przelewy24\Api\Response;

use Przelewy24\Exceptions\ApiResponseException;
use Psr\Http\Message\ResponseInterface;

class PaymentMethodsResponse extends ApiResponse
{
    /**
     * @var array
     */
    protected $methods;

    /**
     * @param $contents
     * @return void
     */
    function prepare($contents)
    {
        if (isset($contents->data)) {
            $this->methods = $contents->data;
        }
    }

    /**
     * @return array
     */
    public function methods()
    {
        return $this->methods ?? [];
    }
}
