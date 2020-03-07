<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215151344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE data_mod (id INT AUTO_INCREMENT NOT NULL, smart_mod_id INT NOT NULL, date_time DATETIME NOT NULL, sa DOUBLE PRECISION NOT NULL, sb DOUBLE PRECISION DEFAULT NULL, sc DOUBLE PRECISION DEFAULT NULL, s3ph DOUBLE PRECISION DEFAULT NULL, k_wh DOUBLE PRECISION DEFAULT NULL, k_varh DOUBLE PRECISION DEFAULT NULL, va DOUBLE PRECISION NOT NULL, vb DOUBLE PRECISION DEFAULT NULL, vc DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5378B2FD2CFA4C13 (smart_mod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_694309E4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smart_mod (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, module_id VARCHAR(255) NOT NULL, associated_site VARCHAR(255) NOT NULL, installation_type VARCHAR(255) NOT NULL, mod_type VARCHAR(255) NOT NULL, INDEX IDX_786B66EEF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user1 (id INT AUTO_INCREMENT NOT NULL, enterprise_name VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_mod ADD CONSTRAINT FK_5378B2FD2CFA4C13 FOREIGN KEY (smart_mod_id) REFERENCES smart_mod (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4A76ED395 FOREIGN KEY (user_id) REFERENCES user1 (id)');
        $this->addSql('ALTER TABLE smart_mod ADD CONSTRAINT FK_786B66EEF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE smart_mod DROP FOREIGN KEY FK_786B66EEF6BD1646');
        $this->addSql('ALTER TABLE data_mod DROP FOREIGN KEY FK_5378B2FD2CFA4C13');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4A76ED395');
        $this->addSql('DROP TABLE data_mod');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE smart_mod');
        $this->addSql('DROP TABLE user1');
    }
}
