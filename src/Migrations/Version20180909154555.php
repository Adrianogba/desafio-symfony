<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration inicial para criação das tabelas empresa e socio.
 */
final class Version20180909154555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação das tabelas empresa e socio';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE empresa (id SERIAL NOT NULL, nome VARCHAR(100) NOT NULL, telefone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE socio (id SERIAL NOT NULL, empresa_id INT NOT NULL, nome VARCHAR(100) NOT NULL, telefone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_SOCIO_EMPRESA_ID ON socio (empresa_id)');
        $this->addSql('ALTER TABLE socio ADD CONSTRAINT FK_SOCIO_EMPRESA FOREIGN KEY (empresa_id) REFERENCES empresa (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE socio DROP CONSTRAINT FK_SOCIO_EMPRESA');
        $this->addSql('DROP TABLE socio');
        $this->addSql('DROP TABLE empresa');
    }
}
