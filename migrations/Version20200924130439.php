<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200924130439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, zip_id INT NOT NULL, region_id INT NOT NULL, departement_id INT NOT NULL, city_id INT NOT NULL, UNIQUE INDEX UNIQ_5E9E89CB7D662686 (zip_id), UNIQUE INDEX UNIQ_5E9E89CB98260155 (region_id), UNIQUE INDEX UNIQ_5E9E89CBCCF9E01E (departement_id), UNIQUE INDEX UNIQ_5E9E89CB8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB7D662686 FOREIGN KEY (zip_id) REFERENCES location_zip (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB98260155 FOREIGN KEY (region_id) REFERENCES location_region (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBCCF9E01E FOREIGN KEY (departement_id) REFERENCES location_departement (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES location_city (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE location');
    }
}
