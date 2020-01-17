<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entities\ProductEntity;

/**
 * Class ProductsListWithTotalDTO
 * @package App\DTO
 */
class ProductsListWithTotalDTO
{
    /**
     * @var int
     */
    public $total = 0;

    /**
     * @var ProductEntity[]
     */
    public $list = [];
}
