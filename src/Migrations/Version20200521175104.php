<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200521175104 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE site ADD subscription VARCHAR(255) NOT NULL, ADD psous DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4A76ED395 FOREIGN KEY (user_id) REFERENCES user1 (id)');
        $this->addSql('ALTER TABLE smart_mod ADD CONSTRAINT FK_786B66EEF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE appro_fuel ADD CONSTRAINT FK_A9B3C11F2CFA4C13 FOREIGN KEY (smart_mod_id) REFERENCES smart_mod (id)');
        $this->addSql('ALTER TABLE data_mod ADD CONSTRAINT FK_5378B2FD2CFA4C13 FOREIGN KEY (smart_mod_id) REFERENCES smart_mod (id)');
        $this->addSql('ALTER TABLE role_user1 ADD CONSTRAINT FK_7B8D6D7AD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user1 ADD CONSTRAINT FK_7B8D6D7A56AE248B FOREIGN KEY (user1_id) REFERENCES user1 (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58F675F31B');
        $this->addSql('ALTER TABLE appro_fuel DROP FOREIGN KEY FK_A9B3C11F2CFA4C13');
        $this->addSql('ALTER TABLE data_mod DROP FOREIGN KEY FK_5378B2FD2CFA4C13');
        $this->addSql('ALTER TABLE role_user1 DROP FOREIGN KEY FK_7B8D6D7AD60322AC');
        $this->addSql('ALTER TABLE role_user1 DROP FOREIGN KEY FK_7B8D6D7A56AE248B');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4A76ED395');
        $this->addSql('ALTER TABLE site DROP subscription, DROP psous');
        $this->addSql('ALTER TABLE smart_mod DROP FOREIGN KEY FK_786B66EEF6BD1646');
    }
}
