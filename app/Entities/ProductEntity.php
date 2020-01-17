<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Class ProductEntity
 * @package App\Entities
 */
class ProductEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     */
    public $created_at;
}
