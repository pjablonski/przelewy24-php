<?php

namespace Przelewy24\Api\Response;

use Przelewy24\Exceptions\ApiResponseException;
use Psr\Http\Message\ResponseInterface;

abstract class ApiResponse
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $code;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $contents = json_decode($response->getBody()->getContents());

        if(isset($contents->data)) {
            $contents = $contents->data;
        }

        if(is_iterable($contents)) {
            foreach ($contents as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        if ($this->hasError()) {
            throw new ApiResponseException(
                $this->getError()
            );
        }
    }

    /**
     * @return bool
     */
    protected function hasError(): bool
    {
        return !empty($this->error);
    }

    /**
     * @return string|null
     */
    protected function getError(): ?string
    {
        return $this->error;
    }
}
