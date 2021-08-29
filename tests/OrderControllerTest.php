<?php

namespace App\Tests;

use App\Repository\Contracts\OrderRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderControllerTest extends KernelTestCase
{
    public function testOrderControllerResponse(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $orderRepo = $container->get(OrderRepositoryInterface::class);
        $params['per_page'] = 10;
        $params['page'] =  1;
        $params['from_date'] =  '2021-09-01';
        $params['to_date'] =  '2021-09-31';
        $params['city'] =  'Cairo';
        $params['country'] =  'EG';
        $orders = $orderRepo->getOrdersByDate($params);
        $this->assertArrayHasKey('data', $orders);
    }
}
