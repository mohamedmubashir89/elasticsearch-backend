<?php

namespace App\Repository\Contracts;

interface OrderRepositoryInterface
{

    /**
     * @param $params
     * @return mixed
     */
    public function getOrdersByDate($params);

    /**
     * @param $params
     * @return mixed
     */
    public function getMostPopularSkuByDate($params);

    /**
     * @param $params
     * @return mixed
     */
    public function getSkuBroughtTogetherByDate($params);

}