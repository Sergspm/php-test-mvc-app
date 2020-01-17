<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Class PayOrderResponseDTO
 * @package App\DTO
 */
class PayOrderResponseDTO
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
