<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Class OrderProductDTO
 * @package App\DTO
 */
class OrderProductDTO
{
    /**
     * @var int|null
     */
    public $productId;
    
    /**
     * @var float|null
     */
    public $productPrice;

    /**
     * OrderProductDTO constructor.
     * @param int $productId
     * @param float $productPrice
     */
    public function __construct(int $productId, float $productPrice)
    {
        $this->productId = $productId;
        $this->productPrice = $productPrice;
    }
}
