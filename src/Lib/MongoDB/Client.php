<?php

namespace App\Lib\MongoDB;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

class Client
{
    public function __construct(
        private readonly string $databaseName,
        private readonly Manager $manager
    ) {
    }

    public function executeQuery(string $collectionName, Query $query): array
    {
        $result = $this->manager->executeQuery($this->databaseName.'.'.$collectionName, $query);

        return json_decode(json_encode($result->toArray(), true), true);
    }

}
