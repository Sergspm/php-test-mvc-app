<?php

declare(strict_types=1);

namespace App\Facades;

/**
 * Class ConfigFacade
 * @package App\Facades
 */
class ConfigFacade
{
    /**
     * @return array
     */
    public static function getDBConfig(): array
    {
        return require __DIR__ . '/../../config/db.php';
    }
}
