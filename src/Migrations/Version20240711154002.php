<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240711154002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial Imoje Plugin migration version';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bitbag_imoje_transaction (id INT AUTO_INCREMENT NOT NULL, payment_id INT DEFAULT NULL, transactionId VARCHAR(64) NOT NULL, paymentUrl VARCHAR(244) DEFAULT NULL, orderId VARCHAR(64) NOT NULL, serviceId VARCHAR(64) NOT NULL, gatewayCode VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_29783A5BC2F43114 (transactionId), INDEX IDX_29783A5B4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bitbag_imoje_transaction ADD CONSTRAINT FK_29783A5B4C3A3BB FOREIGN KEY (payment_id) REFERENCES sylius_payment (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bitbag_imoje_transaction DROP FOREIGN KEY FK_29783A5B4C3A3BB');
        $this->addSql('DROP TABLE bitbag_imoje_transaction');
    }
}
