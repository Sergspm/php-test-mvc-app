<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTO\{CreateOrderResponseDTO, PayOrderResponseDTO};
use App\Services\{OrderService, ProductService};
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrdersController
 * @package App\Controllers
 */
class OrdersController extends Controller
{
    /**
     * @param Request $request
     * @return CreateOrderResponseDTO
     * @throws DBALException
     */
    public function create(Request $request): CreateOrderResponseDTO
    {
        $resultDTO = new CreateOrderResponseDTO();
        $productsIds = array_map('intval', $request->request->get('product_id'));

        if (!$productsIds) {
            return $resultDTO;
        }

        $productsList = (new ProductService())->getProductsByIds($productsIds);

        if (!$productsList) {
            return $resultDTO;
        }

        $createdOrderDTO = (new OrderService())->createOrderFromUserIdAndProducts(1, $productsList);

        $resultDTO->orderId = $createdOrderDTO->orderId ?: 0;
        $resultDTO->success = !!$resultDTO->orderId;

        return $resultDTO;
    }

    /**
     * @param Request $request
     * @return PayOrderResponseDTO
     * @throws DBALException
     */
    public function pay(Request $request): PayOrderResponseDTO
    {
        $resultDTO = new PayOrderResponseDTO();

        $orderId = (int) $request->request->get('order_id', 0);
        $orderSum = (float) $request->request->get('sum', 0);

        if (!$orderId || !$orderSum) {
            return $resultDTO;
        }

        $payOrderDTO = (new OrderService())->payOrder($orderId, $orderSum);

        $resultDTO->success = $payOrderDTO->payed;
        $resultDTO->orderId = $payOrderDTO->orderId;

        return $resultDTO;
    }
}
