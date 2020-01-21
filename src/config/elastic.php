<?php

/*
 * elasticsearch connect
 */

namespace config;

require __DIR__ . '/../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

final class elastic
{
    /**
     * @var
     */
    public static $client;

    /**
     * @return Client
     */
    public static function connect() : Client
    {
        if(!self::$client) {
            self::$client = ClientBuilder::create()->setHosts([getenv('ELASTIC_PORT')])->build();
        }
        return self::$client;
    }

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}
}