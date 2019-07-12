<?php

namespace App\Search\Entity\Interfaces;

use SwaggerSearch\Model\ListItem;

/**
 * Interface EntityInterface
 * @package App\Search\Entity\Interfaces
 * @method getSourceTypeKeyCode
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
     * @param $index
     * @return string
     */
    public function getIndexWithPrefix($index);

    /**
     * @param $data
     * @return mixed
     */
    public function getConvertedEngineData($data) : ListItem;
}