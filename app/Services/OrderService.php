<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\{CreatedOrderDTO, OrderProductDTO, PayOrderDTO};
use App\Entities\ProductEntity;
use App\Repositories\OrderRepository;
use Doctrine\DBAL\DBALException;
use GuzzleHttp\Client;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderService extends Service
{
    /**
     * @param int $userId
     * @param ProductEntity[] $products
     * @return CreatedOrderDTO
     * @throws DBALException
     */
    public function createOrderFromUserIdAndProducts(int $userId, array $products)
    {
        $resultDTO = new CreatedOrderDTO();
        $orderProductsDTOs = array_map(function (ProductEntity $productEntity) {
            return new OrderProductDTO($productEntity->id, $productEntity->price);
        }, $products);

        $orderEntity = (new OrderRepository())->createOrderFromUserIdAndProductIds($userId, $orderProductsDTOs);

        $resultDTO->orderId = $orderEntity->id ?? 0;

        return $resultDTO;
    }

    /**
     * @param int $orderId
     * @param float $sum
     * @return PayOrderDTO
     * @throws DBALException
     */
    public function payOrder(int $orderId, float $sum): PayOrderDTO
    {
        $resultDTO = new PayOrderDTO();

        if (!$orderId) {
            return $resultDTO;
        }

        $orderRepository = new OrderRepository();
        $orderEntity = $orderRepository->getOrderEntityById($orderId);

        if (!$orderEntity) {
            return $resultDTO;
        }

        $resultDTO->orderId = $orderEntity->id;

        if (!$orderEntity->isNew() || $orderEntity->sum !== $sum) {
            return $resultDTO;
        }

        $client = new Client();
        $response = $client->request('GET', 'https://ya.ru/');

        if ($response->getStatusCode() === 200) {
            $resultDTO->payed = $orderRepository->markOrderAsPayed($orderId);
        }

        return $resultDTO;
    }
}
