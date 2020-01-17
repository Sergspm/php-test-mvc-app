<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Class HttpResponseErrorDTO
 * @package App\DTO
 */
class HttpResponseErrorDTO
{
    /**
     * @var bool
     */
    public $success = false;

    /**
     * @var bool
     */
    public $message = false;

    /**
     * HttpResponseErrorDTO constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
