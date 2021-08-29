<?php

namespace App\Traits;

use Elasticsearch\ClientBuilder;
use ONGR\ElasticsearchDSL\Search;

trait ElasticSearchTrait
{
    private $clientBuilder;
    private $index;

    /**
     * @param $searchIndex
     * @param $host
     */
    public function newElasticSearchConnection($searchIndex, $host)
    {
        $this->index = $searchIndex;
        $this->clientBuilder = ClientBuilder::create()->setHosts([$host])->build();
    }

    /**
     * get search result
     *
     * @param array $search
     * @param null $type
     * @return array
     */
    public function getSearchResult(array $search, $type = null): array
    {
        $params = [
            'index' => $this->index,
            'type' => $type,
            'body' => $search
        ];

        return $this->clientBuilder->search(
            $params
        );
    }
}
