<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202143202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE anuncios (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, precio DOUBLE PRECISION NOT NULL, fecha_creacion DATETIME DEFAULT NULL, fecha_modificacion DATETIME DEFAULT NULL, INDEX IDX_9AFBE206DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fotos (id INT AUTO_INCREMENT NOT NULL, anuncio_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, principal TINYINT(1) NOT NULL, INDEX IDX_CB8405C7963066FD (anuncio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(255) DEFAULT NULL, apellido VARCHAR(255) DEFAULT NULL, provincia VARCHAR(255) DEFAULT NULL, telefono VARCHAR(255) DEFAULT NULL, foto VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_EF687F2E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anuncios ADD CONSTRAINT FK_9AFBE206DB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id)');
        $this->addSql('ALTER TABLE fotos ADD CONSTRAINT FK_CB8405C7963066FD FOREIGN KEY (anuncio_id) REFERENCES anuncios (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fotos DROP FOREIGN KEY FK_CB8405C7963066FD');
        $this->addSql('ALTER TABLE anuncios DROP FOREIGN KEY FK_9AFBE206DB38439E');
        $this->addSql('DROP TABLE anuncios');
        $this->addSql('DROP TABLE fotos');
        $this->addSql('DROP TABLE usuarios');
    }
}
