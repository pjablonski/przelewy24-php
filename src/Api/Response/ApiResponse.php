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
        if ($response->getStatusCode() != 200) {
            $this->code = $response->getStatusCode();
            $this->error = $response->getReasonPhrase();
        }

        $contents = json_decode($response->getBody()->getContents());

        $this->prepare($contents);

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

    /**
     * @param $contents
     * @return void
     */
    protected function prepare($contents)
    {
        if (isset($contents->data)) {
            $contents = $contents->data;
        }

        if (is_iterable($contents)) {
            foreach ($contents as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
}
