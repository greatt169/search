<?php

namespace App\Search\Entity\Interfaces;

interface AttributeStorageInterface
{
    public function set(string $key, string $field, string $value): void;

    public function getOne(string $key, $field): string;

    public function getMany(string $key, array $fields): array;
}