<?php


namespace services;

require_once __DIR__ . '/../config/mongodb.php';
require_once __DIR__ . '/../interfaces/DBInterface.php';

use config\mongodb;
use DBInterface;
use MongoDB\BSON\ObjectId;

class MongoService implements DBInterface
{
    /**
     * @var \MongoDB\Collection
     */
    private $collection;

    /**
     * MongoService constructor.
     */
    public function __construct()
    {
        $this->collection = mongodb::connect()->selectCollection(getenv('MONGO_DB'), 'articles');

    }

    /**
     * @param array $data
     * @return string
     */
    public function insert(array $data) : string
    {
        $result = $this->collection->insertOne($data);
        return $result->getInsertedId();
    }

    /**
     * @param string $id
     * @return array
     */
    public function getById(string $id): array
    {
        $article = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $article->getArrayCopy() ?? [];
    }
}