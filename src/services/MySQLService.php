<?php

namespace services;

use config\db;
use interfaces\DBInterface;
use PDO;

class MySQLService implements DBInterface
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * MySQLService constructor.
     */
    public function __construct()
    {
        $this->db = db::connect();
    }

    /**
     * @param array $data
     * @return string
     */
    public function insert(array $data) : string
    {
        $query = $this->db->prepare('INSERT INTO articles (title, description, category, author, short_description) 
                                                VALUES (:title, :description, :category, :author, :short_description)');

        $query->bindParam(':title', $data['title']);
        $query->bindParam(':description', $data['description']);
        $query->bindParam(':category', $data['category']);
        $query->bindParam(':author', $data['author']);
        $query->bindParam(':short_description', $data['short_description']);


        $query->execute();

        return $this->db->lastInsertId();
    }

    /**
     * @param string $id
     * @return array
     */
    public function getById(string $id): array
    {
        try {
            $id = (int)$id;
            $query = $this->db->prepare('SELECT id,title,description,category,author, short_description FROM articles WHERE id = :id');
            $query->bindParam(':id', $id);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $article = $query->fetch();
            if($article) {
                return [
                    'status'    => 'success',
                    'data'      => $article,
                ];
            }
            return [];
        }catch (\PDOException $e) {
            return [
                'status'    => 'error',
                'data'      => $e->getMessage(),
            ];
        }
    }
}