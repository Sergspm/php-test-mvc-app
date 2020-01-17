<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Class OrderEntity
 * @package App\Entities
 */
class OrderEntity
{
    /**
     * @var int Status new
     */
    public const STATUS_NEW = 1;

    /**
     * @var int Status payed
     */
    public const STATUS_PAYED = 2;

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var int
     */
    public $status;

    /**
     * @var float
     */
    public $sum;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $payed_at;

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }
}
