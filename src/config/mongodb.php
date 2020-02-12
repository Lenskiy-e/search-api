<?php

/*
 * Connect to MongoDB
 */

namespace config;

use MongoDB\Client;

class mongodb
{
    /**
     * @var
     */
    private static $client;

    /**
     * @return Client
     */
    public static function connect()
    {
        if(!self::$client) {
            self::$client = new Client(getenv('MONGO_URI'));
        }
        return self::$client;
    }

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}
}