<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Class PayOrderDTO
 * @package App\DTO
 */
class PayOrderDTO
{
    /**
     * @var bool
     */
    public $payed = false;

    /**
     * @var int
     */
    public $orderId = 0;
}
