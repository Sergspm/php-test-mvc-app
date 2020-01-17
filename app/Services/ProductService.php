<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductsListWithTotalDTO;
use App\Repositories\ProductRepository;
use Doctrine\DBAL\DBALException;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService extends Service
{
    /**
     * @var array
     */
    private $rawProductsDataQueue = [];

    /**
     * @param array $rawProductsData
     * @return $this
     * @throws DBALException
     */
    public function createProductsFromRawData(array $rawProductsData): self
    {
        $productRepository = new ProductRepository();

        foreach ($rawProductsData as $productData) {
            $productRepository->addProductToQueue($productRepository->prepareEntityFromRawArray($productData));
        }

        $productRepository
            ->batchCreateProductsFromQueue()
            ->clearProductsQueue();

        return $this;
    }

    /**
     * @param array $ids
     * @return array
     * @throws DBALException
     */
    public function getProductsByIds(array $ids): array
    {
        return $ids ? (new ProductRepository())->getProductsByIds($ids) : [];
    }

    /**
     * @return $this
     * @throws DBALException
     */
    public function createProductsFromRawDataQueue(): self
    {
        return $this->createProductsFromRawData($this->rawProductsDataQueue);
    }

    /**
     * @return $this
     */
    public function clearRawProductsDataQueue(): self
    {
        $this->rawProductsDataQueue = [];

        return $this;
    }

    /**
     * @param array $rawProductData
     * @return $this
     */
    public function pushRawProductDataToCreateQueue(array $rawProductData): self
    {
        $this->rawProductsDataQueue[] = $rawProductData;

        return $this;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return ProductsListWithTotalDTO
     * @throws DBALException
     */
    public function getProductsListForPage(int $page, int $perPage): ProductsListWithTotalDTO
    {
        return (new ProductRepository())->getProductsWithLimitsOrderById($perPage, $page * $perPage - $perPage);
    }
}
