<?php

/*
 * Main configure class
 */

namespace config;

use services\ElasticService;
use interfaces\DBInterface;

final class bootstrap
{
    /**
     * @var
     */
    public $db;
    /**
     * @var
     */
    public $elastic;

    /**
     * bootstrap constructor.
     */
    public function __construct()
    {
        $f = fopen(__DIR__ . '/.env', 'r');
        $params = explode(PHP_EOL, fread($f, filesize(__DIR__ . '/.env')));

        foreach ($params as $param) {
            if($param) {
                putenv($param);
            }
        }
    }

    /**
     * @param DBInterface $db
     * @return bootstrap
     */
    public function start(DBInterface $db): bootstrap
    {
        $this->db = $db;
        $this->elastic = new ElasticService();
        return $this;
    }

}