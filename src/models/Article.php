<?php

namespace models;

require_once __DIR__ . '/../services/MongoService.php';
require_once __DIR__ . '/../services/MySQLService.php';

use config\bootstrap;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use services\MongoService;
use services\MySQLService;

class Article
{
    /**
     * @var bootstrap
     */
    private $app;

    /**
     * Article constructor.
     * @param bootstrap $bootstrap
     */
    public function __construct(bootstrap $bootstrap)
    {
        /*
         * Can be MongoService or MySQLService
         */
        $this->app = $bootstrap->start(new MongoService());
    }

    /**
     * @param array $data
     * @return array
     */
    public function insert(array $data) : array
    {
        try {
            $id = $this->app->db->insert($data);
            $this->app->elastic->index($id, $data);
            $this->app->elastic->fullIndex($id, $data);
            return [
                'status'    => 'success',
                'data'   => '',
            ];
        }catch (\PDOException $e) {
            return $this->sendError($e->getMessage());
        }catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }catch (Missing404Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Search by multiple field
     * @param array $data
     * @return array
     */
    public function searchMany(array $data) : array
    {
        try {
            $ids = [];
            $searchTypes = ['title', 'description', 'author', 'category', 'short_description'];

            foreach ($data as $key => $value) {
                if(!in_array($key, $searchTypes,true)) {
                    return $this->sendError('Bad field');
                }
            }

            $results = $this->app->elastic->multiplySearch($data);

            foreach ($results['hits']['hits'] as $result) {
                $ids[] = $result['_id'];
            }
            return [
                'status'    => 'success',
                'data'   => $ids,
            ];
        }catch (Missing404Exception $e) {
            return $this->sendError($e->getMessage());
        }catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Search by one field
     * @param string $type
     * @param string $query
     * @return array
     */
    public function search(string $type, string $query) : array
    {
        try {
            $ids = [];
            $searchTypes = ['title', 'description', 'author', 'category', 'short_description'];
            if(!in_array($type, $searchTypes, true)) {
                return $this->sendError('Bad field');
            }

            foreach ($this->app->elastic->search($type, $query) as $row) {
                $ids[] = $row['_id'];
            }

            return [
                'status'    =>  'success',
                'data'      =>  $ids,
            ];
        }catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function searchById(string $id)
    {
        return $this->app->db->getById($id);
    }

    /**
     * @param string $message
     * @return array
     */
    private function sendError(string $message) : array
    {
        return [
            'status'    => 'error',
            'data'      => $message
        ];
    }
}