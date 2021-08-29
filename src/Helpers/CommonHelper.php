<?php
namespace App\Helpers;

use ONGR\ElasticsearchDSL\Query\Specialized\ScriptQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\RangeQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;

class CommonHelper
{
    /**
     * format date
     * @param $date
     * @param string $format
     * @return false|string
     */
    public function formatDate($date, $format='Y-m-d')
    {
        return date($format, strtotime($date));
    }

    /**
     * get only the source data
     * @param $data
     * @return array
     */
    public function processData($data): array
    {
        $dataArr = [];
        if ($data) {
            foreach ($data['hits']['hits'] as $hit) {
                $dataArr[] = $hit['_source'];
            }
        }
        return $dataArr;
    }

    /**
     * get only the aggregate data
     * @param $field
     * @param $data
     * @return array
     */
    public function processAggregateData($field, $data): array
    {
        return $data? $data['aggregations'][$field]['buckets'] : $data;
    }

    /**
     * get total record
     * @param $data
     * @return int
     */
    public function getTotalRecord($data): int
    {
        $total = 0;
        if ($data) {
            $total = $data['hits']['total']['value'];
        }
        return $total;
    }

    /**
     * set pagination
     * @param Search $search
     * @param PaginatorHelper $pagination
     * @return Search
     */
    public function setElasticPagination(Search $search, PaginatorHelper $pagination): Search
    {
        $search->setSize($pagination->getPerPage());
        $search->setFrom($pagination->getPageNo());

        return $search;
    }

    /**
     * @param $field
     * @param $fromDate
     * @param $toDate
     * @return RangeQuery
     */
    public function getDateRangeQuery($field, $fromDate, $toDate): RangeQuery
    {
        return new RangeQuery(
            $field,
            [
                'gte' => $fromDate,
                'lte' => $toDate,
                'boost' => 2.0,
            ]
        );
    }


    /**
     * @param $field
     * @param $value
     * @return TermQuery
     */
    public function getTermQuery($field, $value): TermQuery
    {
        return new TermQuery(
            $field, $value
        );
    }

    /**
     * @param $script
     * @return ScriptQuery
     */
    public function getScriptQuery($script): ScriptQuery
    {
        return new ScriptQuery(
            $script
        );
    }
}