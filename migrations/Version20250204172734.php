<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204172734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE podcast (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE podcast_category (podcast_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(podcast_id, category_id))');
        $this->addSql('CREATE INDEX IDX_E633B1E8786136AB ON podcast_category (podcast_id)');
        $this->addSql('CREATE INDEX IDX_E633B1E812469DE2 ON podcast_category (category_id)');
        $this->addSql('CREATE TABLE podcast_user (podcast_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(podcast_id, user_id))');
        $this->addSql('CREATE INDEX IDX_66B74805786136AB ON podcast_user (podcast_id)');
        $this->addSql('CREATE INDEX IDX_66B74805A76ED395 ON podcast_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE user_subscriptions (user_source INT NOT NULL, user_target INT NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX IDX_EAF927513AD8644E ON user_subscriptions (user_source)');
        $this->addSql('CREATE INDEX IDX_EAF92751233D34C1 ON user_subscriptions (user_target)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE podcast_category ADD CONSTRAINT FK_E633B1E8786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE podcast_category ADD CONSTRAINT FK_E633B1E812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE podcast_user ADD CONSTRAINT FK_66B74805786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE podcast_user ADD CONSTRAINT FK_66B74805A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_subscriptions ADD CONSTRAINT FK_EAF927513AD8644E FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_subscriptions ADD CONSTRAINT FK_EAF92751233D34C1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE podcast_category DROP CONSTRAINT FK_E633B1E8786136AB');
        $this->addSql('ALTER TABLE podcast_category DROP CONSTRAINT FK_E633B1E812469DE2');
        $this->addSql('ALTER TABLE podcast_user DROP CONSTRAINT FK_66B74805786136AB');
        $this->addSql('ALTER TABLE podcast_user DROP CONSTRAINT FK_66B74805A76ED395');
        $this->addSql('ALTER TABLE user_subscriptions DROP CONSTRAINT FK_EAF927513AD8644E');
        $this->addSql('ALTER TABLE user_subscriptions DROP CONSTRAINT FK_EAF92751233D34C1');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE podcast');
        $this->addSql('DROP TABLE podcast_category');
        $this->addSql('DROP TABLE podcast_user');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_subscriptions');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
