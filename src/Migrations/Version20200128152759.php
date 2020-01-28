<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128152759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE anuncios ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE anuncios ADD CONSTRAINT FK_9AFBE206DB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id)');
        $this->addSql('CREATE INDEX IDX_9AFBE206DB38439E ON anuncios (usuario_id)');
        $this->addSql('ALTER TABLE fotos ADD anuncio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fotos ADD CONSTRAINT FK_CB8405C7963066FD FOREIGN KEY (anuncio_id) REFERENCES anuncios (id)');
        $this->addSql('CREATE INDEX IDX_CB8405C7963066FD ON fotos (anuncio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE anuncios DROP FOREIGN KEY FK_9AFBE206DB38439E');
        $this->addSql('DROP INDEX IDX_9AFBE206DB38439E ON anuncios');
        $this->addSql('ALTER TABLE anuncios DROP usuario_id');
        $this->addSql('ALTER TABLE fotos DROP FOREIGN KEY FK_CB8405C7963066FD');
        $this->addSql('DROP INDEX IDX_CB8405C7963066FD ON fotos');
        $this->addSql('ALTER TABLE fotos DROP anuncio_id');
    }
}
