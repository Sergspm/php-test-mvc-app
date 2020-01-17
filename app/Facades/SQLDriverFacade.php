<?php

declare(strict_types=1);

namespace App\Facades;

use Doctrine\DBAL\{Connection, DBALException, DriverManager};

/**
 * Class SQLDriverFacade
 * @package App\Facades
 */
class SQLDriverFacade
{
    /**
     * @var Connection|null
     */
    private static $conn;

    /**
     * @return Connection
     * @throws DBALException
     */
    public static function getDBALConnection(): Connection
    {
        if (!self::$conn) {
            self::$conn = DriverManager::getConnection(ConfigFacade::getDBConfig());
        }
        return self::$conn;
    }

    /**
     * @param Connection $connection
     * @return void
     */
    public static function setConnection(Connection $connection): void
    {
        self::$conn = $connection;
    }
}
