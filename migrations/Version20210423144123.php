<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423144123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lockers ADD source_id INT NOT NULL, ADD external_id INT NOT NULL, ADD county_id INT NOT NULL, ADD city_id INT NOT NULL, ADD supported_payment INT NOT NULL, DROP sourceId, DROP externalId, DROP countyId, DROP cityId, DROP supportedPayment, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE status status INT NOT NULL, CHANGE postalcode postal_code VARCHAR(20) NOT NULL');
        $this->addSql('DROP INDEX lockerId ON lockers_boxes');
        $this->addSql('ALTER TABLE lockers_boxes ADD id INT AUTO_INCREMENT NOT NULL, ADD locker_id INT NOT NULL, ADD box_type INT NOT NULL, DROP lockerId, DROP boxType, CHANGE status status INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE lockers_boxes_types CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE status status INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX locker_schedule_id ON lockers_schedule');
        $this->addSql('ALTER TABLE lockers_schedule ADD id INT AUTO_INCREMENT NOT NULL, ADD opening_hour VARCHAR(10) NOT NULL, ADD closing_hour VARCHAR(10) NOT NULL, DROP openingHour, DROP closingHour, CHANGE lockerid locker_id INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE lockers_source CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE status status INT NOT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lockers ADD sourceId INT NOT NULL, ADD externalId INT NOT NULL, ADD countyId INT NOT NULL, ADD cityId INT NOT NULL, ADD supportedPayment INT DEFAULT 1 NOT NULL, DROP source_id, DROP external_id, DROP county_id, DROP city_id, DROP supported_payment, CHANGE id id INT NOT NULL, CHANGE email email VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE status status INT DEFAULT 0 NOT NULL, CHANGE postal_code postalCode VARCHAR(20) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE lockers_boxes MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE lockers_boxes DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE lockers_boxes ADD lockerId INT NOT NULL, ADD boxType INT NOT NULL, DROP id, DROP locker_id, DROP box_type, CHANGE status status INT DEFAULT 1 NOT NULL COMMENT \'0 - occupied boxes, 1 - available boxes, 2 - reserverd boxes\'');
        $this->addSql('CREATE INDEX lockerId ON lockers_boxes (lockerId)');
        $this->addSql('ALTER TABLE lockers_boxes_types MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE lockers_boxes_types DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE lockers_boxes_types CHANGE id id INT NOT NULL, CHANGE status status INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE lockers_schedule MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE lockers_schedule DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE lockers_schedule ADD openingHour VARCHAR(10) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, ADD closingHour VARCHAR(10) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, DROP id, DROP opening_hour, DROP closing_hour, CHANGE locker_id lockerId INT NOT NULL');
        $this->addSql('CREATE INDEX locker_schedule_id ON lockers_schedule (lockerId)');
        $this->addSql('ALTER TABLE lockers_source MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE lockers_source DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE lockers_source CHANGE id id INT NOT NULL, CHANGE status status INT DEFAULT 1 NOT NULL');
    }
}
