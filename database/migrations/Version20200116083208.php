<?php

declare(strict_types=1);

namespace database\migrations;

use App\Facades\SQLDriverFacade;
use App\Services\ProductService;
use database\fakers\CarsFaker;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200116083208
 * @package database\migrations
 */
final class Version20200116083208 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     * @throws DBALException
     */
    public function up(Schema $schema): void
    {
        SQLDriverFacade::setConnection($this->connection);

        $productService = new ProductService();
        $carFaker = new CarsFaker();

        for ($i = 0; $i < 20; ++$i) {
            $productService->pushRawProductDataToCreateQueue($carFaker->getRandomCarTitleWithPrice());
        }

        $productService
            ->createProductsFromRawDataQueue()
            ->clearRawProductsDataQueue();

        $this->addSql('SELECT 1');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE products');
    }
}
