<?php
namespace App\Search\UseCases\Errors;

use App\Search\UseCases\Errors\Interfaces\ApiExceptionInterface;
use App\Search\UseCases\Config;
use \SwaggerSearch\Model\Error as SwaggerError;
use Throwable;

class Error extends SwaggerError
{
    protected $httpCode = 500;

    const CODE_ELEMENT_NOT_FOUND = 'ElementNotFound';
    const FILE_NOT_FOUND = 'FileNotFound';
    const CODE_BAD_REQUEST = 'BadRequest';
    const CODE_INTERNAL_SERVER_ERROR = 'InternalServerError';

    protected function getCodesMapping()
    {
        return [
            self::CODE_ELEMENT_NOT_FOUND => 404,
            self::CODE_BAD_REQUEST => 400,
            self::FILE_NOT_FOUND => 400,
        ];
    }

    public function getError(Throwable $exception): Error
    {
        if (!Config::isProdMode()) {
            $this->setDebug($exception->getTraceAsString());
            $message = mb_convert_encoding($exception->getMessage(), 'UTF-8', 'UTF-8');
            $this->setMessage($message);
        }
        $this->httpCode = 500;

        if ($exception instanceof ApiExceptionInterface) {
            /**
             * @var ApiExceptionInterface $exception
             */
            $appCode = $exception->getAppCode();
            $arrCodesMapping = $this->getCodesMapping();
            if (array_key_exists($appCode, $arrCodesMapping)) {
                $httpCode = $arrCodesMapping[$appCode];
                $this->httpCode = $httpCode;
            }
            $this->setApplicationErrorCode($appCode);
        }
        if (!$this->getApplicationErrorCode()) {
            $this->setApplicationErrorCode(self::CODE_INTERNAL_SERVER_ERROR);
        }

        return $this;
    }

    /**
     * @param int $httpCode
     */
    public function setHttpCode(int $httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
