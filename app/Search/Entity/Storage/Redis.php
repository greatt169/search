<?php

namespace App\Search\Entity\Storage;

use App\Search\Entity\Interfaces\AttributeStorageInterface;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis as RedisFacade;

class Redis implements AttributeStorageInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct()
    {
        $this->connection = RedisFacade::connection();
    }

    public function set(string $key, string $field, string $value): void
    {
        $this->connection->hset($key, $field, $value);
    }

    public function getOne(string $key, $field): string
    {
        return  $this->connection->hget($key, $field);
    }

    public function getMany(string $key, array $fields): array
    {
        return $this->connection->hmget($key, $fields);
    }
}