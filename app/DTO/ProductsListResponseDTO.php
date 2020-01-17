<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entities\ProductEntity;

/**
 * Class ProductsListResponseDTO
 * @package App\DTO
 */
class ProductsListResponseDTO
{
    /**
     * @var int
     */
    public $total = 0;

    /**
     * @var int
     */
    public $page = 0;

    /**
     * @var int
     */
    public $perPage = 0;

    /**
     * @var ProductEntity[]
     */
    public $list = [];

    /**
     * ProductsListResponseDTO constructor.
     * @param int $page
     * @param int $perPage
     */
    public function __construct(int $page, int $perPage)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }
}
