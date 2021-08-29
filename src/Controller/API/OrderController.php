<?php

namespace App\Controller\API;

use App\Controller\AppBaseController;
use App\Repository\Contracts\OrderRepositoryInterface;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AppBaseController
{
    /**
     * @var OrderRepository
     */
    private $orderRepo;
    private const VALIDATION_ERROR = 'Missing required field';

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrdersByDate(Request $request): JsonResponse
    {
        try {
            $input = $request->query->all();
            return $this->sendResponse(
                $this->orderRepo
                    ->getOrdersByDate($input), 'Successfully order data retrieved'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error Occurred', 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getMostPopularSkuByDate(Request $request): JsonResponse
    {
        try {
            return $this->sendResponse(
                $this->orderRepo
                    ->getMostPopularSkuByDate($request->query->all()), 'Successfully popular sku data retrieved'
            );
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSkuBroughtTogetherByDate(Request $request): JsonResponse
    {
        try {
            return $this->sendResponse(
                $this->orderRepo
                    ->getSkuBroughtTogetherByDate($request->query->all()), 'Successfully sku brought together data retrieved'
            );
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 404);
        }
    }
}
