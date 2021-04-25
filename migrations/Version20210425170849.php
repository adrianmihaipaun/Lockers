<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425170849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lockers (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, external_id INT NOT NULL, name VARCHAR(100) NOT NULL, county VARCHAR(100) NOT NULL, county_id INT NOT NULL, city VARCHAR(100) NOT NULL, city_id INT NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(20) NOT NULL, lat VARCHAR(30) NOT NULL, lng VARCHAR(30) NOT NULL, supported_payment INT NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(15) NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockers_boxes (id INT AUTO_INCREMENT NOT NULL, locker_id INT NOT NULL, size VARCHAR(20) NOT NULL, number INT NOT NULL, locker_boxes_type_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockers_boxes_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockers_schedule (id INT AUTO_INCREMENT NOT NULL, locker_id INT NOT NULL, day INT NOT NULL, opening_hour VARCHAR(10) NOT NULL, closing_hour VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lockers_source (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lockers');
        $this->addSql('DROP TABLE lockers_boxes');
        $this->addSql('DROP TABLE lockers_boxes_types');
        $this->addSql('DROP TABLE lockers_schedule');
        $this->addSql('DROP TABLE lockers_source');
    }
}
