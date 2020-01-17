<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\OrderProductDTO;
use App\Entities\OrderEntity;
use App\Exceptions\{BadInsertIdException, EntityNotFoundException};
use Doctrine\DBAL\{Connection, DBALException};
use Exception;

/**
 * Class OrderRepository
 * @package App\Repositories
 */
class OrderRepository extends Repository
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'orders';
    }

    /**
     * @return string
     */
    public static function getOrderProductEntityTableName(): string
    {
        return 'order_products';
    }

    /**
     * @param int $orderId
     * @return OrderEntity|null
     * @throws DBALException
     */
    public function getOrderEntityById(int $orderId): ?OrderEntity
    {
        if ($orderId) {
            $orderData = self::getConnection()
                ->createQueryBuilder()
                ->select('*')
                ->from(self::getTableName())
                ->where("id = {$orderId}")
                ->execute()
                ->fetch();

            if ($orderData) {
                return $this->prepareEntityFromRawArray($orderData);
            }
        }

        return null;
    }

    /**
     * @param int $userId
     * @param OrderProductDTO[] $orderProductsDTOs
     * @return OrderEntity
     * @throws DBALException
     */
    public function createOrderFromUserIdAndProductIds(int $userId, array $orderProductsDTOs): ?OrderEntity
    {
        $orderEntity = null;
        $connection = self::getConnection();

        $connection->beginTransaction();

        $orderSum = $this->getOrderSumFromOrderProductsDTOs($orderProductsDTOs);

        try {
            $this->insertNewOrder($connection, $userId, $orderSum);

            $orderId = (int) $connection->lastInsertId();

            if (!$orderId) {
                throw new BadInsertIdException();
            }

            $orderEntity = $this->getOrderEntityById($orderId);

            if (!$orderEntity) {
                throw new EntityNotFoundException();
            }

            $this->insertOrderProducts($connection, $orderId, $orderProductsDTOs);

            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
        }

        return $orderEntity;
    }

    /**
     * @param int $orderId
     * @return bool
     * @throws DBALException
     */
    public function markOrderAsPayed(int $orderId): bool
    {
        return $orderId
            ? (bool) self::getConnection()
                ->createQueryBuilder()
                ->update(self::getTableName())
                ->set('status', OrderEntity::STATUS_PAYED)
                ->set('payed_at', 'NOW()')
                ->where("id = {$orderId}")
                ->execute()
            : false;
    }

    /**
     * @param array $rawEntity
     * @return OrderEntity
     */
    public function prepareEntityFromRawArray(array $rawEntity): OrderEntity
    {
        $orderEntity = new OrderEntity();

        $orderEntity->id = isset($rawEntity['id']) ? (int) $rawEntity['id'] :  null;
        $orderEntity->user_id = isset($rawEntity['user_id']) ? (int) $rawEntity['user_id'] :  null;
        $orderEntity->status = isset($rawEntity['status']) ? (int) $rawEntity['status'] :  null;
        $orderEntity->sum = isset($rawEntity['sum']) ? (float) $rawEntity['sum'] :  null;
        $orderEntity->created_at = $rawEntity['created_at'] ??  null;
        $orderEntity->payed_at = $rawEntity['payed_at'] ??  null;

        return $orderEntity;
    }

    /**
     * @param Connection $connection
     * @param int $userId
     * @param float $orderSum
     * @return int
     */
    private function insertNewOrder(Connection $connection, int $userId, float $orderSum): int
    {
        return $connection
            ->createQueryBuilder()
            ->insert(self::getTableName())
            ->values([
                'user_id' => $userId,
                'status' => OrderEntity::STATUS_NEW,
                'sum' => $orderSum,
            ])
            ->execute();
    }

    /**
     * @param Connection $connection
     * @param int $orderId
     * @param OrderProductDTO[] $orderProductsDTOs
     */
    private function insertOrderProducts(Connection $connection, int $orderId, array $orderProductsDTOs): void
    {
        foreach ($orderProductsDTOs as $orderProductDTO) {
            $connection
                ->createQueryBuilder()
                ->insert(self::getOrderProductEntityTableName())
                ->values([
                    'order_id' => $orderId,
                    'product_id' => $orderProductDTO->productId,
                ])
                ->execute();
        }
    }

    /**
     * @param OrderProductDTO[] $orderProductsDTOs
     * @return float
     */
    private function getOrderSumFromOrderProductsDTOs(array $orderProductsDTOs): float
    {
        $orderSum = 0;

        foreach ($orderProductsDTOs as $orderProductDTO) {
            $orderSum += $orderProductDTO->productPrice;
        }

        return (float) $orderSum;
    }
}
