<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Class OrderProductEntity
 * @package App\Entities
 */
class OrderProductEntity
{
    /**
     * @var int
     */
    public $order_id;

    /**
     * @var int
     */
    public $product_id;

    /**
     * @var string
     */
    public $created_at;
}
