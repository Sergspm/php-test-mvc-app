<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Facades\SQLDriverFacade;
use Doctrine\DBAL\{Connection, DBALException};

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository
{
    /**
     * @return string
     */
    abstract public static function getTableName(): string;

    /**
     * @return Connection
     * @throws DBALException
     */
    protected static function getConnection(): Connection
    {
        return SQLDriverFacade::getDBALConnection();
    }
}
