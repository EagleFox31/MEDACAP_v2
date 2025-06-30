<?php

namespace Infrastructure\Mongo;

use MongoDB\Client;

class MongoConnection
{
    private $client;
    private $db;

    public function __construct(string $uri, string $dbName)
    {
        $this->client = new Client($uri);
        $this->db = $this->client->selectDatabase($dbName);
    }

    public function getDatabase()
    {
        return $this->db;
    }
}
