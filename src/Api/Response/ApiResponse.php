<?php

namespace Przelewy24\Api\Response;

use Przelewy24\Exceptions\ApiResponseException;
use Przelewy24\Exceptions\NotFoundException;
use Psr\Http\Message\ResponseInterface;

abstract class ApiResponse
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Przelewy24\Exceptions\ApiResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        if ($response->getStatusCode() != 200) {
            $this->error = $response->getStatusCode();
            $this->errorMessage = $response->getReasonPhrase();
        }

        $contents = json_decode($response->getBody()->getContents());

        $this->prepare($contents);

        if ($this->hasError()) {
            switch ($this->getError()) {
                case 404:
                    throw new NotFoundException(
                        $this->getErrorMessage(),
                        $this->getError()
                    );
                default:
                    throw new ApiResponseException(
                        $this->getErrorMessage(),
                        $this->getError()
                    );
            }
        }
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

        $contents = (array)$contents;

        if (is_iterable($contents)) {
            foreach ($contents as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
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
     * @return int|null
     */
    protected function getError(): ?int
    {
        return $this->error;
    }

    /**
     * @return string|null
     */
    protected function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
