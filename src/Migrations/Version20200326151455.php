<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200326151455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE appro_fuel (id INT AUTO_INCREMENT NOT NULL, smart_mod_id INT NOT NULL, add_at DATETIME NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_A9B3C11F2CFA4C13 (smart_mod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appro_fuel ADD CONSTRAINT FK_A9B3C11F2CFA4C13 FOREIGN KEY (smart_mod_id) REFERENCES smart_mod (id)');
        $this->addSql('ALTER TABLE smart_mod ADD critiq_fuel_stock DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE data_mod ADD stock DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE appro_fuel');
        $this->addSql('ALTER TABLE data_mod DROP stock');
        $this->addSql('ALTER TABLE smart_mod DROP critiq_fuel_stock');
    }
}
