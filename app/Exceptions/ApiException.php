<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    protected $applicationCode;

    protected $debug;

    public function __construct(string $message = "", $debug, int $code = 0, $applicationCode = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->debug = $debug;
        $this->applicationCode = $applicationCode ? $applicationCode : $code;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response([
            'application_error_code' => $this->getApplicationCode(),
            'debug' => $this->getDebug(),
            'message' => $this->getMessage(),
        ], $this->getApplicationCode());
    }

    /**
     * @return mixed
     */
    public function getApplicationCode()
    {
        return $this->applicationCode;
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->debug;
    }
}