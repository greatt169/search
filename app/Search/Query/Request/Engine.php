<?php

namespace App\Search\Query\Request;

use App\Exceptions\ApiException;
use App\Search\Query\Interfaces\RequestEngineInterface;

abstract class Engine implements RequestEngineInterface
{
    protected static $instances = [];

    protected $engine;

    protected function __construct($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param $engine
     * @return mixed
     * @throws ApiException
     */
    public static function getInstance($engine)
    {
        $className = __NAMESPACE__ . "\\" . mb_convert_case($engine, MB_CASE_TITLE, "UTF-8");
        if (!array_key_exists($engine, static::$instances)) {

            if(!class_exists($className)) {
                $debugMessage = sprintf('Class %s not found for engine %s', $className, $engine);
                throw new ApiException('Internal Server Error', $debugMessage, 500);
            }

            static::$instances[$engine] = app()->make($className, ['engine' => $engine]);
        }
        return static::$instances[$engine];
    }
}