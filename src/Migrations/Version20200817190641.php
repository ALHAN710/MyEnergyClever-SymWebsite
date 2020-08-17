<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200817190641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE data_mod ADD pamoy DOUBLE PRECISION DEFAULT NULL, ADD pamax DOUBLE PRECISION DEFAULT NULL, ADD pbmoy DOUBLE PRECISION DEFAULT NULL, ADD pbmax DOUBLE PRECISION DEFAULT NULL, ADD pcmoy DOUBLE PRECISION DEFAULT NULL, ADD pcmax DOUBLE PRECISION DEFAULT NULL, ADD qamoy DOUBLE PRECISION DEFAULT NULL, ADD qamax DOUBLE PRECISION DEFAULT NULL, ADD qbmoy DOUBLE PRECISION DEFAULT NULL, ADD qbmax DOUBLE PRECISION DEFAULT NULL, ADD qcmoy DOUBLE PRECISION DEFAULT NULL, ADD qcmax DOUBLE PRECISION DEFAULT NULL, ADD samax DOUBLE PRECISION DEFAULT NULL, ADD sbmax DOUBLE PRECISION DEFAULT NULL, ADD scmax DOUBLE PRECISION DEFAULT NULL, ADD cosamin DOUBLE PRECISION DEFAULT NULL, ADD cosamn DOUBLE PRECISION DEFAULT NULL, ADD cosbmin DOUBLE PRECISION DEFAULT NULL, ADD cosbmn DOUBLE PRECISION DEFAULT NULL, ADD coscmin DOUBLE PRECISION DEFAULT NULL, ADD coscmn DOUBLE PRECISION DEFAULT NULL, ADD pmax3ph DOUBLE PRECISION DEFAULT NULL, ADD pmoy3ph DOUBLE PRECISION DEFAULT NULL, ADD qmax3ph DOUBLE PRECISION DEFAULT NULL, ADD qmoy3ph DOUBLE PRECISION DEFAULT NULL, ADD cosmin3ph DOUBLE PRECISION NOT NULL, ADD cosmn3ph DOUBLE PRECISION DEFAULT NULL, ADD vamin DOUBLE PRECISION DEFAULT NULL, ADD vamax DOUBLE PRECISION DEFAULT NULL, ADD vbmin DOUBLE PRECISION DEFAULT NULL, ADD vbmax DOUBLE PRECISION DEFAULT NULL, ADD vcmin DOUBLE PRECISION DEFAULT NULL, ADD vcmax DOUBLE PRECISION DEFAULT NULL, ADD kwha DOUBLE PRECISION DEFAULT NULL, ADD kwhb DOUBLE PRECISION DEFAULT NULL, ADD kwhc DOUBLE PRECISION DEFAULT NULL, ADD era DOUBLE PRECISION DEFAULT NULL, ADD erb DOUBLE PRECISION DEFAULT NULL, ADD erc DOUBLE PRECISION DEFAULT NULL, ADD thdia DOUBLE PRECISION DEFAULT NULL, ADD thdib DOUBLE PRECISION DEFAULT NULL, ADD thdic DOUBLE PRECISION DEFAULT NULL, ADD thdi3ph DOUBLE PRECISION DEFAULT NULL, ADD dmoya DOUBLE PRECISION DEFAULT NULL, ADD dmoyb DOUBLE PRECISION DEFAULT NULL, ADD dmoyc DOUBLE PRECISION DEFAULT NULL, ADD dmoy3ph DOUBLE PRECISION DEFAULT NULL, ADD idmoy DOUBLE PRECISION DEFAULT NULL, ADD iomoy DOUBLE PRECISION DEFAULT NULL, ADD vdmoy DOUBLE PRECISION DEFAULT NULL, ADD vomoy DOUBLE PRECISION DEFAULT NULL, ADD fdimoy DOUBLE PRECISION DEFAULT NULL, ADD fdvmoy DOUBLE PRECISION DEFAULT NULL, ADD iddmoy DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE data_mod DROP pamoy, DROP pamax, DROP pbmoy, DROP pbmax, DROP pcmoy, DROP pcmax, DROP qamoy, DROP qamax, DROP qbmoy, DROP qbmax, DROP qcmoy, DROP qcmax, DROP samax, DROP sbmax, DROP scmax, DROP cosamin, DROP cosamn, DROP cosbmin, DROP cosbmn, DROP coscmin, DROP coscmn, DROP pmax3ph, DROP pmoy3ph, DROP qmax3ph, DROP qmoy3ph, DROP cosmin3ph, DROP cosmn3ph, DROP vamin, DROP vamax, DROP vbmin, DROP vbmax, DROP vcmin, DROP vcmax, DROP kwha, DROP kwhb, DROP kwhc, DROP era, DROP erb, DROP erc, DROP thdia, DROP thdib, DROP thdic, DROP thdi3ph, DROP dmoya, DROP dmoyb, DROP dmoyc, DROP dmoy3ph, DROP idmoy, DROP iomoy, DROP vdmoy, DROP vomoy, DROP fdimoy, DROP fdvmoy, DROP iddmoy');
    }
}
