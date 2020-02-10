<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210143018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_trip (user_id INT NOT NULL, trip_id INT NOT NULL, INDEX IDX_CD7B9F2A76ED395 (user_id), INDEX IDX_CD7B9F2A5BC2E0E (trip_id), PRIMARY KEY(user_id, trip_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_trip ADD CONSTRAINT FK_CD7B9F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_trip ADD CONSTRAINT FK_CD7B9F2A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trip ADD user_id INT NOT NULL, ADD school_id INT NOT NULL, ADD state_id INT NOT NULL, ADD location_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BC32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B64D218E FOREIGN KEY (location_id) REFERENCES trip_location (id)');
        $this->addSql('CREATE INDEX IDX_7656F53BA76ED395 ON trip (user_id)');
        $this->addSql('CREATE INDEX IDX_7656F53BC32A47EE ON trip (school_id)');
        $this->addSql('CREATE INDEX IDX_7656F53B5D83CC1 ON trip (state_id)');
        $this->addSql('CREATE INDEX IDX_7656F53B64D218E ON trip (location_id)');
        $this->addSql('ALTER TABLE trip_location ADD city_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip_location ADD CONSTRAINT FK_F6CFBADB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_F6CFBADB8BAC62AF ON trip_location (city_id)');
        $this->addSql('ALTER TABLE user ADD school_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C32A47EE ON user (school_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_trip');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BA76ED395');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BC32A47EE');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B5D83CC1');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B64D218E');
        $this->addSql('DROP INDEX IDX_7656F53BA76ED395 ON trip');
        $this->addSql('DROP INDEX IDX_7656F53BC32A47EE ON trip');
        $this->addSql('DROP INDEX IDX_7656F53B5D83CC1 ON trip');
        $this->addSql('DROP INDEX IDX_7656F53B64D218E ON trip');
        $this->addSql('ALTER TABLE trip DROP user_id, DROP school_id, DROP state_id, DROP location_id');
        $this->addSql('ALTER TABLE trip_location DROP FOREIGN KEY FK_F6CFBADB8BAC62AF');
        $this->addSql('DROP INDEX IDX_F6CFBADB8BAC62AF ON trip_location');
        $this->addSql('ALTER TABLE trip_location DROP city_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C32A47EE');
        $this->addSql('DROP INDEX IDX_8D93D649C32A47EE ON user');
        $this->addSql('ALTER TABLE user DROP school_id');
    }
}
