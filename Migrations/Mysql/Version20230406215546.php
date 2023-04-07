<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406215546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE nextbox_neos_urlshortener_domain_model_urlshortener ADD node VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE nextbox_neos_urlshortener_domain_model_urlshortener ADD CONSTRAINT FK_AA55665A857FE845 FOREIGN KEY (node) REFERENCES neos_contentrepository_domain_model_nodedata (persistence_object_identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA55665A857FE845 ON nextbox_neos_urlshortener_domain_model_urlshortener (node)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE nextbox_neos_urlshortener_domain_model_urlshortener DROP FOREIGN KEY FK_AA55665A857FE845');
        $this->addSql('DROP INDEX UNIQ_AA55665A857FE845 ON nextbox_neos_urlshortener_domain_model_urlshortener');
        $this->addSql('ALTER TABLE nextbox_neos_urlshortener_domain_model_urlshortener DROP node');
    }
}
