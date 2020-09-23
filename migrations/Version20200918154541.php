<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918154541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city_zip DROP FOREIGN KEY FK_6F2BEFCE8BAC62AF');
        $this->addSql('ALTER TABLE city_zip DROP FOREIGN KEY FK_6F2BEFCE7D662686');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(255) NOT NULL, zip_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_zip');
        $this->addSql('DROP TABLE zip');
        $this->addSql('ALTER TABLE user DROP zip');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE city_zip (city_id INT NOT NULL, zip_id INT NOT NULL, INDEX IDX_6F2BEFCE8BAC62AF (city_id), INDEX IDX_6F2BEFCE7D662686 (zip_id), PRIMARY KEY(city_id, zip_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE zip (id INT AUTO_INCREMENT NOT NULL, code_id INT NOT NULL, UNIQUE INDEX UNIQ_421D954627DAFE17 (code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE city_zip ADD CONSTRAINT FK_6F2BEFCE7D662686 FOREIGN KEY (zip_id) REFERENCES zip (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_zip ADD CONSTRAINT FK_6F2BEFCE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE zip ADD CONSTRAINT FK_421D954627DAFE17 FOREIGN KEY (code_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE location');
        $this->addSql('ALTER TABLE user ADD zip VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
