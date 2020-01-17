<?php

declare(strict_types=1);

namespace database\fakers;

/**
 * Class CarsFaker
 * @package database\fakers
 */
class CarsFaker
{
    /**
     * @var array
     */
    private $rawData;

    /**
     * CarsFaker constructor.
     */
    public function __construct()
    {
        $this->rawData = json_decode(file_get_contents(__DIR__ . '/data/mercedes-models.json'), true);
    }

    /**
     * @return array
     */
    public function getRandomCarTitleWithPrice(): array
    {
        return [
            'title' => 'Mercedes-Benz ' . ($this->rawData[rand(0, count($this->rawData) - 1)]['name'] ?? ''),
            'price' => (float) rand(100 * 1000, 7 * 1000 * 1000),
        ];
    }
}
