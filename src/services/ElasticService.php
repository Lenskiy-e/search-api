<?php

namespace services;

use config\elastic;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ElasticService
{
    /**
     * @var \Elasticsearch\Client
     */
    private $client;

    /**
     * ElasticService constructor.
     */
    public function __construct()
    {
        $this->client = elastic::connect();
    }

    /**
     * @param string $id
     * @param array $data
     */
    public function index(string $id, array $data) : void
    {
        foreach ($data as $key => $value) {
            $params = [
                'index' => $key,
                'type'  => 'text',
                'id'    => $id,
                'body'  => [
                    $key => $value
                ],
            ];
            $this->client->index($params);
        }
    }

    /**
     * @param string $id
     * @param array $data
     */
    public function fullIndex(string $id, array $data) : void
    {
        $params['body'][] = [
          'index' => [
              '_index'  => 'articles',
              '_id'     => $id
          ],
        ];
        $params['body'][] = [
            'title'  => $data['title'],
            'description'  => $data['description'],
            'category'  => $data['category'],
            'author'  => $data['author'],
            'short_description'  => $data['short_description'],
        ];

        $this->client->bulk($params);
    }


    /**
     * @param string $index
     * @param string $query
     * @return array
     */
    public function search(string $index, string $query) : array
    {
        $searchData = [
            'index' => $index,
            'body'  => [
                'query' => [
                    'match' => [
                        $index => $query
                    ],
                ],
            ],
        ];

        $result = $this->client->search($searchData);
        return $result['hits']['hits'];
    }

    /**
     * @param array $data
     * @return array
     */
    public function multiplySearch(array $data)
    {
        $params = [
            'index' => 'articles',
            'body'  => [
                'query' => [
                    'bool'  =>[
                        'must' => [],
                    ],
                ],
            ],
        ];

        foreach ($data as $key => $value) {
            $params['body']['query']['bool']['must'][] = ['match' => [$key => $value]];
        }

        return $this->client->search($params);
    }

}