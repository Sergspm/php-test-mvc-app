<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTO\ProductsListResponseDTO;
use App\Services\ProductService;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductsController
 * @package App\Controllers
 */
class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @return ProductsListResponseDTO
     * @throws DBALException
     */
    public function index(Request $request): ProductsListResponseDTO
    {
        $resultDTO = new ProductsListResponseDTO(abs((int) $request->get('page', 1)), 10);

        $productsDTO = (new ProductService())->getProductsListForPage($resultDTO->page, $resultDTO->perPage);

        $resultDTO->list = $productsDTO->list;
        $resultDTO->total = $productsDTO->total;

        return $resultDTO;
    }
}
