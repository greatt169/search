<?php

namespace App\Search\Query\Request;

use App\Exceptions\ApiException;
use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Query\Interfaces\RequestEngineInterface;

abstract class Engine implements RequestEngineInterface
{
    protected static $instances = [];

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var string
     */
    protected $engine;

    /**
     * @var string
     */
    protected $index;

    /**
     * Engine constructor.
     * @param $engine
     * @param $index
     * @param EntityInterface $entity
     * @throws ApiException
     */
    protected function __construct($engine, $index, EntityInterface $entity)
    {
        $this->entity = $entity;
        $this->engine = $engine;
        $indexWithPrefix = $this->entity->getIndexWithPrefix($index);
        $aliasIndex = $this->entity->getIndexByAlias($indexWithPrefix);
        $this->index = $aliasIndex;
    }

    /**
     * @param $engine
     * @param $index
     * @return mixed
     * @throws ApiException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function getInstance($engine, $index)
    {
        $className = __NAMESPACE__ . "\\" . mb_convert_case($engine, MB_CASE_TITLE, "UTF-8");
        if (!array_key_exists($engine, static::$instances)) {
            if(!class_exists($className)) {
                $debugMessage = sprintf('Class %s not found for engine %s', $className, $engine);
                throw new ApiException('Internal Server Error', $debugMessage, 500);
            }
            static::$instances[$engine] = app()->make($className, ['engine' => $engine, 'index' => $index]);
        }
        return static::$instances[$engine];
    }
}