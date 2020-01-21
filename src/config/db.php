<?php

/*
 * PDO conntection
 */

namespace config;

use PDO;

final class db
{
    /**
     * @var
     */
    private static $pdo;
    /**
     * @var
     */
    private static $migrate;

    /**
     * @return PDO
     */
    public static function connect() : PDO
    {
        if(!self::$pdo) {
            try {
                $host = getenv('DB_HOST');
                $port = getenv('DB_PORT');
                $db = getenv('DB_NAME');
                $login = getenv('DB_LOGIN');
                $pass = getenv('DB_PASS');

                self::$pdo = new PDO("mysql:host={$host};port={$port};dbname={$db};", $login, $pass);
            }catch (\PDOException $e) {
                die($e->getMessage());
            }
        }
        /*
         * Check if already migrate table
         */
        if(!self::$migrate && !self::checkTable()) {
            self::migrate();
        }

        return self::$pdo;
    }

    /**
     * @return bool
     */
    private static function checkTable() : bool
    {
        $query = self::$pdo->query('SHOW TABLES');
        $result = $query->fetchAll(PDO::FETCH_COLUMN);

        return !empty($result) && in_array('articles', $result, true);
    }


    /**
     * @return bool|string
     */
    private static function migrate()
    {
        try {
            $result = self::$pdo->query("CREATE TABLE articles ( id INT NOT NULL AUTO_INCREMENT , title VARCHAR(255) NOT NULL , description TEXT NOT NULL , category VARCHAR(255) NOT NULL , author VARCHAR(255) NOT NULL , short_description TEXT NOT NULL , PRIMARY KEY (id))");
            if(!$result) {
                return false;
            }
            return true;
        }catch (\PDOException $e) {
            return $e->getMessage();
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}
}