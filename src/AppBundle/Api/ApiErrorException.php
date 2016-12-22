<?php

namespace AppBundle\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiErrorException extends HttpException
{

    /**
     * @var ApiErrorMessage
     */
    private $errorMessage;

    public function __construct(ApiErrorMessage $errorMessage, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->errorMessage = $errorMessage;
        $statusCode = $errorMessage->getStatusCode();
        $message = $errorMessage->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);

    }

    public function getApiError()
    {
        return $this->errorMessage;
    }
}
