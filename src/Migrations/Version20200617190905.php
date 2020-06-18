<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617190905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arrival (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_volunteer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, is_morning TINYINT(1) NOT NULL, is_afternoon TINYINT(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_9E46A0ABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shelter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, departure_id INT NOT NULL, arrival_id INT NOT NULL, volunteer_id INT DEFAULT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_morning TINYINT(1) NOT NULL, is_afternoon TINYINT(1) NOT NULL, INDEX IDX_7656F53B7704ED06 (departure_id), INDEX IDX_7656F53B62789708 (arrival_id), INDEX IDX_7656F53B8EFAB6B1 (volunteer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip_user (trip_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A6AB4522A5BC2E0E (trip_id), INDEX IDX_A6AB4522A76ED395 (user_id), PRIMARY KEY(trip_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, mobicoop_id INT NOT NULL, status VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule_volunteer ADD CONSTRAINT FK_9E46A0ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B7704ED06 FOREIGN KEY (departure_id) REFERENCES departure (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B62789708 FOREIGN KEY (arrival_id) REFERENCES arrival (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip_user ADD CONSTRAINT FK_A6AB4522A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trip_user ADD CONSTRAINT FK_A6AB4522A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B62789708');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B7704ED06');
        $this->addSql('ALTER TABLE trip_user DROP FOREIGN KEY FK_A6AB4522A5BC2E0E');
        $this->addSql('ALTER TABLE schedule_volunteer DROP FOREIGN KEY FK_9E46A0ABA76ED395');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B8EFAB6B1');
        $this->addSql('ALTER TABLE trip_user DROP FOREIGN KEY FK_A6AB4522A76ED395');
        $this->addSql('DROP TABLE arrival');
        $this->addSql('DROP TABLE departure');
        $this->addSql('DROP TABLE schedule_volunteer');
        $this->addSql('DROP TABLE shelter');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE trip_user');
        $this->addSql('DROP TABLE user');
    }
}
