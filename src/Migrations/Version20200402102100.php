<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200402102100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ad (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, introduction LONGTEXT NOT NULL, content LONGTEXT NOT NULL, cover_image VARCHAR(255) NOT NULL, rooms INT NOT NULL, INDEX IDX_77E0ED58F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user1 (id INT AUTO_INCREMENT NOT NULL, enterprise_name VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, enterprise_logo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_694309E4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smart_mod (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, module_id VARCHAR(255) NOT NULL, installation_type VARCHAR(255) NOT NULL, mod_type VARCHAR(255) NOT NULL, mod_name VARCHAR(255) NOT NULL, critiq_fuel_stock DOUBLE PRECISION DEFAULT NULL, INDEX IDX_786B66EEF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appro_fuel (id INT AUTO_INCREMENT NOT NULL, smart_mod_id INT NOT NULL, add_at DATETIME NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_A9B3C11F2CFA4C13 (smart_mod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_mod (id INT AUTO_INCREMENT NOT NULL, smart_mod_id INT NOT NULL, date_time DATETIME NOT NULL, sa DOUBLE PRECISION NOT NULL, sb DOUBLE PRECISION DEFAULT NULL, sc DOUBLE PRECISION DEFAULT NULL, s3ph DOUBLE PRECISION DEFAULT NULL, k_wh DOUBLE PRECISION DEFAULT NULL, k_varh DOUBLE PRECISION DEFAULT NULL, va DOUBLE PRECISION NOT NULL, vb DOUBLE PRECISION DEFAULT NULL, vc DOUBLE PRECISION DEFAULT NULL, s3phmax DOUBLE PRECISION DEFAULT NULL, liters DOUBLE PRECISION DEFAULT NULL, workingtime BIGINT DEFAULT NULL, stock DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5378B2FD2CFA4C13 (smart_mod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, ad_id INT NOT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_C53D045F4F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user1 (role_id INT NOT NULL, user1_id INT NOT NULL, INDEX IDX_7B8D6D7AD60322AC (role_id), INDEX IDX_7B8D6D7A56AE248B (user1_id), PRIMARY KEY(role_id, user1_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, hash VARCHAR(255) NOT NULL, introduction VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4A76ED395 FOREIGN KEY (user_id) REFERENCES user1 (id)');
        $this->addSql('ALTER TABLE smart_mod ADD CONSTRAINT FK_786B66EEF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE appro_fuel ADD CONSTRAINT FK_A9B3C11F2CFA4C13 FOREIGN KEY (smart_mod_id) REFERENCES smart_mod (id)');
        $this->addSql('ALTER TABLE data_mod ADD CONSTRAINT FK_5378B2FD2CFA4C13 FOREIGN KEY (smart_mod_id) REFERENCES smart_mod (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE role_user1 ADD CONSTRAINT FK_7B8D6D7AD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user1 ADD CONSTRAINT FK_7B8D6D7A56AE248B FOREIGN KEY (user1_id) REFERENCES user1 (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4F34D596');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4A76ED395');
        $this->addSql('ALTER TABLE role_user1 DROP FOREIGN KEY FK_7B8D6D7A56AE248B');
        $this->addSql('ALTER TABLE smart_mod DROP FOREIGN KEY FK_786B66EEF6BD1646');
        $this->addSql('ALTER TABLE appro_fuel DROP FOREIGN KEY FK_A9B3C11F2CFA4C13');
        $this->addSql('ALTER TABLE data_mod DROP FOREIGN KEY FK_5378B2FD2CFA4C13');
        $this->addSql('ALTER TABLE role_user1 DROP FOREIGN KEY FK_7B8D6D7AD60322AC');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58F675F31B');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE user1');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE smart_mod');
        $this->addSql('DROP TABLE appro_fuel');
        $this->addSql('DROP TABLE data_mod');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user1');
        $this->addSql('DROP TABLE user');
    }
}
