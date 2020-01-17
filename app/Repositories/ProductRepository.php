<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\ProductsListWithTotalDTO;
use App\Entities\ProductEntity;
use Doctrine\DBAL\{Connection, DBALException};

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class ProductRepository extends Repository
{
    /**
     * @var ProductEntity[]
     */
    private $productsQueue = [];

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'products';
    }

    /**
     * @param ProductEntity[] $products
     * @return $this
     * @throws DBALException
     */
    public function batchCreateProducts(array $products): self
    {
        if ($products) {
            $conn = self::getConnection();

            foreach ($products as $productEntity) {
                $conn->createQueryBuilder()
                    ->insert(static::getTableName())
                    ->values(['title' => '?', 'price' => '?'])
                    ->setParameter(0, $productEntity->title)
                    ->setParameter(1, $productEntity->price)
                    ->execute();
            }
        }

        return $this;
    }

    /**
     * @param array $ids
     * @return array
     * @throws DBALException
     */
    public function getProductsByIds(array $ids): array
    {
        $entitiesList = [];

        $query = self::getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from(self::getTableName());

        $queryResult = $query
            ->where($query->expr()->in('id', ':ids'))
            ->setParameter('ids', $ids, Connection::PARAM_STR_ARRAY)
            ->execute()
            ->fetchAll();

        foreach ($queryResult as $queryResultRow) {
            $entitiesList[] = $this->prepareEntityFromRawArray($queryResultRow);
        }

        return $entitiesList;
    }

    /**
     * @return $this
     * @throws DBALException
     */
    public function batchCreateProductsFromQueue(): self
    {
        return $this->batchCreateProducts($this->productsQueue);
    }

    /**
     * @param ProductEntity $productEntity
     * @return $this
     */
    public function addProductToQueue(ProductEntity $productEntity): self
    {
        $this->productsQueue[] = $productEntity;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearProductsQueue(): self
    {
        $this->productsQueue[] = [];

        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return ProductsListWithTotalDTO
     * @throws DBALException
     */
    public function getProductsWithLimitsOrderById(int $limit, int $offset): ProductsListWithTotalDTO
    {
        $resultDTO = new ProductsListWithTotalDTO();

        $dbResult = self::getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from(static::getTableName())
            ->orderBy('id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->execute()
            ->fetchAll();

        foreach ($dbResult as $rawEntity) {
            $resultDTO->list[] = $this->prepareEntityFromRawArray($rawEntity);
        }

        $resultDTO->total = (int) self::getConnection()
            ->createQueryBuilder()
            ->select('COUNT(id)')
            ->from(static::getTableName())
            ->execute()
            ->fetchColumn();

        return $resultDTO;
    }

    /**
     * @param array $rawEntity
     * @return ProductEntity
     */
    public function prepareEntityFromRawArray(array $rawEntity): ProductEntity
    {
        $productEntity = new ProductEntity();

        $productEntity->id = isset($rawEntity['id']) ? (int) $rawEntity['id'] :  null;
        $productEntity->title = $rawEntity['title'] ?? null;
        $productEntity->price = isset($rawEntity['price']) ? (float) $rawEntity['price'] :  null;
        $productEntity->created_at = $rawEntity['created_at'] ?? null;

        return $productEntity;
    }
}
