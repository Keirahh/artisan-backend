<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925084436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP INDEX UNIQ_5E9E89CB8BAC62AF, ADD INDEX IDX_5E9E89CB8BAC62AF (city_id)');
        $this->addSql('ALTER TABLE location DROP INDEX UNIQ_5E9E89CBCCF9E01E, ADD INDEX IDX_5E9E89CBCCF9E01E (departement_id)');
        $this->addSql('ALTER TABLE location DROP INDEX UNIQ_5E9E89CB7D662686, ADD INDEX IDX_5E9E89CB7D662686 (zip_id)');
        $this->addSql('ALTER TABLE location DROP INDEX UNIQ_5E9E89CB98260155, ADD INDEX IDX_5E9E89CB98260155 (region_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP INDEX IDX_5E9E89CB7D662686, ADD UNIQUE INDEX UNIQ_5E9E89CB7D662686 (zip_id)');
        $this->addSql('ALTER TABLE location DROP INDEX IDX_5E9E89CB98260155, ADD UNIQUE INDEX UNIQ_5E9E89CB98260155 (region_id)');
        $this->addSql('ALTER TABLE location DROP INDEX IDX_5E9E89CBCCF9E01E, ADD UNIQUE INDEX UNIQ_5E9E89CBCCF9E01E (departement_id)');
        $this->addSql('ALTER TABLE location DROP INDEX IDX_5E9E89CB8BAC62AF, ADD UNIQUE INDEX UNIQ_5E9E89CB8BAC62AF (city_id)');
    }
}
