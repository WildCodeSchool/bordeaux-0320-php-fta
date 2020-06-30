<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629122630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip ADD beneficiary_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7656F53BECCAAFA0 ON trip (beneficiary_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BECCAAFA0');
        $this->addSql('DROP INDEX IDX_7656F53BECCAAFA0 ON trip');
        $this->addSql('ALTER TABLE trip DROP beneficiary_id');
    }
}
