<?php

namespace App\Repository;

use App\Helpers\CommonHelper;
use App\Helpers\PaginatorHelper;
use App\Repository\Contracts\OrderRepositoryInterface;
use App\Traits\ElasticSearchTrait;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\Search;

class OrderRepository implements OrderRepositoryInterface
{
    use ElasticSearchTrait;

    protected const INDEX = 'kibana_sample_data_ecommerce';
    private $host;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * OrderRepository constructor.
     * @param $elasticsearch_host
     * @param CommonHelper $commonHelper
     */
    public function __construct($elasticsearch_host, CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
        $this->host = $elasticsearch_host;
        $this->newElasticSearchConnection(self::INDEX, $this->host);
    }

    /**
     * get order by date
     * @param $params
     * @return mixed|void
     */
    public function getOrdersByDate($params): array
    {
        $search = new Search();

        if (isset($params['country']) && !empty($params['country'])) {
            $countryQuery = $this->commonHelper->getTermQuery('geoip.country_iso_code', $params['country']);
            $search->addQuery($countryQuery);
        }

        if (isset($params['city']) && !empty($params['city'])) {
            $cityQuery = $this->commonHelper->getTermQuery('geoip.city_name', $params['city']);
            $search->addQuery($cityQuery);
        }

        $this->prepareDateRangeQuery('order_date', $params, $search);

        // pagination
        $pagination = new PaginatorHelper($params);
        $search = $this->commonHelper->setElasticPagination($search, $pagination);

        $queryArray = $search->toArray();
        $queryData = $this->getSearchResult($queryArray);

        $processData = $this->commonHelper->processData($queryData);
        $totalRecord = $this->commonHelper->getTotalRecord($queryData);
        return [
            'data' => $processData,
            'total' => $totalRecord,
            'page' => $pagination->getPageNo(),
        ];
    }

    /**
     * get most popular sku by date range
     * @param $params
     * @return mixed|void
     */
    public function getMostPopularSkuByDate($params): array
    {
        $termsAggregation = new TermsAggregation('products', 'sku');
        $termsAggregation->addParameter('size', 5); // return top 5
        $search = new Search();
        $search->addAggregation($termsAggregation);

        $this->prepareDateRangeQuery('order_date', $params, $search);

        $queryArray = $search->toArray();
        $queryData = $this->getSearchResult($queryArray);
        $processData = $this->commonHelper->processAggregateData('products', $queryData);
        return [
            'data' => $processData,
        ];
    }

    /**
     * get sku brought together by date range
     * @param $params
     * @return array
     */
    public function getSkuBroughtTogetherByDate($params): array
    {
        $scriptQuery = $this->commonHelper->getScriptQuery("doc['sku'].size() > 1");

        $search = new Search();
        $search->addQuery($scriptQuery);

        $this->prepareDateRangeQuery('order_date', $params, $search);

        // pagination
        $pagination = new PaginatorHelper($params);
        $search = $this->commonHelper->setElasticPagination($search, $pagination);

        $queryArray = $search->toArray();
        $queryData = $this->getSearchResult($queryArray);

        $processData = $this->commonHelper->processData($queryData);
        $totalRecord = $this->commonHelper->getTotalRecord($queryData);
        return [
            'data' => $processData,
            'total' => $totalRecord,
            'page' => $pagination->getPageNo(),
        ];
    }

    /**
     * @param $field
     * @param $params
     * @param $search
     */
    private function prepareDateRangeQuery($field, $params, Search &$search)
    {
        if ((isset($params['from_date']) && !empty($params['from_date']))
            && (isset($params['to_date']) && !empty($params['to_date']))) {
            $fromDate = $this->commonHelper->formatDate($params['from_date']);
            $toDate = $this->commonHelper->formatDate($params['to_date']);
            $dateQuery = $this->commonHelper->getDateRangeQuery($field, $fromDate, $toDate);
            $search->addQuery($dateQuery);
        }
    }
}