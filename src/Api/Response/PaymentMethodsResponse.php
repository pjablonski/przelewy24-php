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

    public function __construct(ResponseInterface $response)
    {
        $contents = json_decode($response->getBody()->getContents());

        if (isset($contents->data)) {
            $this->methods = $contents->data;
        } else {
            $this->error = $contents->error ?? null;
            $this->errorMessage = $contents->errorMessage ?? null;
        }

        if ($this->hasError()) {
            throw new ApiResponseException(
                $this->getError()
            );
        }
    }

    public function methods()
    {
        return $this->methods;
    }
}
