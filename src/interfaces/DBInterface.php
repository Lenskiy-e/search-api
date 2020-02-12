<?php

namespace interfaces;

interface DBInterface {
    /**
     * DBInterface constructor.
     */
    public function __construct();

    /**
     * @param array $data
     * @return string
     */
    public function insert(array $data) : string;
}