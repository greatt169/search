<?php

namespace App\Search\Entity\Interfaces;

use SwaggerUnAuth\Model\ListItem;

/**
 * Interface EntityInterface
 * @package App\Search\Entity\Interfaces
 *
 */
interface EntityInterface
{
    public function getClient();

    /**
     * @param string $aliasName код alias
     *
     * @return null | string
     */
    public function getIndexByAlias($aliasName);

    /**
     * @param $aliasName
     * @return string
     */
    public function getAliasWithPrefix($aliasName);

    /**
     * @param $data
     * @return mixed
     */
    public function getConvertedEngineData($data) : ListItem;
}