<?php

namespace App\Search\Entity\Interfaces;

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
}