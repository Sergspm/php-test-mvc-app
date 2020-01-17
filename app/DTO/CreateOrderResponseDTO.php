<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Class CreateOrderResponseDTO
 * @package App\DTO
 */
class CreateOrderResponseDTO
{
    /**
     * @var bool
     */
    public $success = false;

    /**
     * @var int
     */
    public $orderId = 0;
}
