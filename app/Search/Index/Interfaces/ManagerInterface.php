<?php

namespace App\Search\Index\Interfaces;

interface ManagerInterface
{
    public function createIndex();

    public function dropIndex();

    public function indexAll();

    public function removeAll();

    public function indexElement($id);
}