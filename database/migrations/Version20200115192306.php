<?php

declare(strict_types=1);

namespace database\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200115192306
 * @package database\migrations
 */
final class Version20200115192306 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema) : void
    {
        $this->addSql("
            CREATE TABLE `products` (
                `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255),
                `price` decimal(10,2) NOT NULL DEFAULT '0.00',
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ");
        $this->addSql("
            CREATE TABLE `orders` (
                `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `user_id` bigint DEFAULT 0,
                `status` smallint DEFAULT 0,
                `sum` decimal(10,2) NOT NULL DEFAULT '0.00',
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `payed_at` TIMESTAMP DEFAULT NULL,
                KEY `orders_user_id` (`user_id`),
                KEY `orders_status` (`status`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ");
        $this->addSql("
            CREATE TABLE `order_products` (
                `order_id` bigint NOT NULL,
                `product_id` bigint NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`order_id`, `product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE IF EXISTS `products`');
        $this->addSql('DROP TABLE IF EXISTS `orders`');
        $this->addSql('DROP TABLE IF EXISTS `order_products`');
    }
}
