<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230710060158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE airtel_money (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, status VARCHAR(30) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, reference_number VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announcement (id INT AUTO_INCREMENT NOT NULL, user_activity_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, abstract VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, publication_date DATE DEFAULT NULL, modification_date DATE DEFAULT NULL, INDEX IDX_4DB9D91C28DBA903 (user_activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announcement_poster (id INT AUTO_INCREMENT NOT NULL, announcement_id INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', preferred_name VARCHAR(100) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, file_name VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_35A4BAA9913AEA17 (announcement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_to_user_question (id INT AUTO_INCREMENT NOT NULL, user_question_id INT DEFAULT NULL, answered_by_id INT DEFAULT NULL, date_answered DATE DEFAULT NULL, answer LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_3A95D967B73C3F2A (user_question_id), INDEX IDX_3A95D9672FC55A77 (answered_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, associated_user_activity_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, publication_date DATE DEFAULT NULL, creation_date DATE DEFAULT NULL, modification_date DATE DEFAULT NULL, abstract VARCHAR(300) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_23A0E66356C3F1C (associated_user_activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, legal_case_id INT DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, email VARCHAR(75) DEFAULT NULL, postal_code VARCHAR(50) DEFAULT NULL, town VARCHAR(50) DEFAULT NULL, neighborhood VARCHAR(50) DEFAULT NULL, street VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_4C62E63882B4A9B (legal_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE final_report (id INT AUTO_INCREMENT NOT NULL, legal_case_id INT DEFAULT NULL, subject LONGTEXT DEFAULT NULL, legal_analysis LONGTEXT DEFAULT NULL, legal_advice LONGTEXT DEFAULT NULL, date DATE DEFAULT NULL, pdf_directory_path VARCHAR(150) DEFAULT NULL, pdf_file_name VARCHAR(70) DEFAULT NULL, UNIQUE INDEX UNIQ_3421FC9382B4A9B (legal_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE final_report_signature (id INT AUTO_INCREMENT NOT NULL, final_report_id INT DEFAULT NULL, admin_signature_xcoord DOUBLE PRECISION DEFAULT NULL, admin_signature_ycoord DOUBLE PRECISION DEFAULT NULL, consultant_signature_xcoord DOUBLE PRECISION DEFAULT NULL, consultant_signature_ycoord DOUBLE PRECISION DEFAULT NULL, signed_document_direct_path VARCHAR(150) DEFAULT NULL, signed_document_file_name VARCHAR(100) DEFAULT NULL, signature_page INT DEFAULT NULL, UNIQUE INDEX UNIQ_D0BA40B16D747D42 (final_report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incomplete_message (id INT AUTO_INCREMENT NOT NULL, legal_case_id INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8FA4780482B4A9B (legal_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_subscription (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(50) DEFAULT NULL, paid TINYINT(1) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal_case (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, assigned_by_id INT DEFAULT NULL, assigned_to_id INT DEFAULT NULL, category_id INT DEFAULT NULL, reference_no VARCHAR(100) DEFAULT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, case_presentation LONGTEXT DEFAULT NULL, creation_date DATE DEFAULT NULL, modification_date DATE DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, expectations LONGTEXT DEFAULT NULL, assignment_date DATE DEFAULT NULL, INDEX IDX_557377B3B03A8386 (created_by_id), INDEX IDX_557377B36E6F1246 (assigned_by_id), INDEX IDX_557377B3F4BD7827 (assigned_to_id), INDEX IDX_557377B312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal_case_category (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(75) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal_case_document (id INT AUTO_INCREMENT NOT NULL, legal_case_id INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', preferred_name VARCHAR(100) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, file_name VARCHAR(50) DEFAULT NULL, INDEX IDX_A016F83582B4A9B (legal_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE our_partner (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE our_partner_logo (id INT AUTO_INCREMENT NOT NULL, our_partner_id INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', preferred_name VARCHAR(100) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, file_name VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_845F3F07FF072B8E (our_partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, airtel_money_id INT DEFAULT NULL, legal_case_id INT DEFAULT NULL, method VARCHAR(20) DEFAULT NULL, paid TINYINT(1) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_6D28840D6EC122A7 (airtel_money_id), UNIQUE INDEX UNIQ_6D28840D82B4A9B (legal_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rejection_motive (id INT AUTO_INCREMENT NOT NULL, legal_case_id INT DEFAULT NULL, motive LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_6FFFBFF282B4A9B (legal_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, status LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_activity (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_4CF9ED5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_details (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_2A2B1580A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_question (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(200) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, status VARCHAR(15) DEFAULT NULL, date_asked DATE DEFAULT NULL, question VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role_choice (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announcement ADD CONSTRAINT FK_4DB9D91C28DBA903 FOREIGN KEY (user_activity_id) REFERENCES user_activity (id)');
        $this->addSql('ALTER TABLE announcement_poster ADD CONSTRAINT FK_35A4BAA9913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
        $this->addSql('ALTER TABLE answer_to_user_question ADD CONSTRAINT FK_3A95D967B73C3F2A FOREIGN KEY (user_question_id) REFERENCES user_question (id)');
        $this->addSql('ALTER TABLE answer_to_user_question ADD CONSTRAINT FK_3A95D9672FC55A77 FOREIGN KEY (answered_by_id) REFERENCES user_activity (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66356C3F1C FOREIGN KEY (associated_user_activity_id) REFERENCES user_activity (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63882B4A9B FOREIGN KEY (legal_case_id) REFERENCES legal_case (id)');
        $this->addSql('ALTER TABLE final_report ADD CONSTRAINT FK_3421FC9382B4A9B FOREIGN KEY (legal_case_id) REFERENCES legal_case (id)');
        $this->addSql('ALTER TABLE final_report_signature ADD CONSTRAINT FK_D0BA40B16D747D42 FOREIGN KEY (final_report_id) REFERENCES final_report (id)');
        $this->addSql('ALTER TABLE incomplete_message ADD CONSTRAINT FK_8FA4780482B4A9B FOREIGN KEY (legal_case_id) REFERENCES legal_case (id)');
        $this->addSql('ALTER TABLE legal_case ADD CONSTRAINT FK_557377B3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE legal_case ADD CONSTRAINT FK_557377B36E6F1246 FOREIGN KEY (assigned_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE legal_case ADD CONSTRAINT FK_557377B3F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE legal_case ADD CONSTRAINT FK_557377B312469DE2 FOREIGN KEY (category_id) REFERENCES legal_case_category (id)');
        $this->addSql('ALTER TABLE legal_case_document ADD CONSTRAINT FK_A016F83582B4A9B FOREIGN KEY (legal_case_id) REFERENCES legal_case (id)');
        $this->addSql('ALTER TABLE our_partner_logo ADD CONSTRAINT FK_845F3F07FF072B8E FOREIGN KEY (our_partner_id) REFERENCES our_partner (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D6EC122A7 FOREIGN KEY (airtel_money_id) REFERENCES airtel_money (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D82B4A9B FOREIGN KEY (legal_case_id) REFERENCES legal_case (id)');
        $this->addSql('ALTER TABLE rejection_motive ADD CONSTRAINT FK_6FFFBFF282B4A9B FOREIGN KEY (legal_case_id) REFERENCES legal_case (id)');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_details ADD CONSTRAINT FK_2A2B1580A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announcement DROP FOREIGN KEY FK_4DB9D91C28DBA903');
        $this->addSql('ALTER TABLE announcement_poster DROP FOREIGN KEY FK_35A4BAA9913AEA17');
        $this->addSql('ALTER TABLE answer_to_user_question DROP FOREIGN KEY FK_3A95D967B73C3F2A');
        $this->addSql('ALTER TABLE answer_to_user_question DROP FOREIGN KEY FK_3A95D9672FC55A77');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66356C3F1C');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63882B4A9B');
        $this->addSql('ALTER TABLE final_report DROP FOREIGN KEY FK_3421FC9382B4A9B');
        $this->addSql('ALTER TABLE final_report_signature DROP FOREIGN KEY FK_D0BA40B16D747D42');
        $this->addSql('ALTER TABLE incomplete_message DROP FOREIGN KEY FK_8FA4780482B4A9B');
        $this->addSql('ALTER TABLE legal_case DROP FOREIGN KEY FK_557377B3B03A8386');
        $this->addSql('ALTER TABLE legal_case DROP FOREIGN KEY FK_557377B36E6F1246');
        $this->addSql('ALTER TABLE legal_case DROP FOREIGN KEY FK_557377B3F4BD7827');
        $this->addSql('ALTER TABLE legal_case DROP FOREIGN KEY FK_557377B312469DE2');
        $this->addSql('ALTER TABLE legal_case_document DROP FOREIGN KEY FK_A016F83582B4A9B');
        $this->addSql('ALTER TABLE our_partner_logo DROP FOREIGN KEY FK_845F3F07FF072B8E');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D6EC122A7');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D82B4A9B');
        $this->addSql('ALTER TABLE rejection_motive DROP FOREIGN KEY FK_6FFFBFF282B4A9B');
        $this->addSql('ALTER TABLE user_activity DROP FOREIGN KEY FK_4CF9ED5AA76ED395');
        $this->addSql('ALTER TABLE user_details DROP FOREIGN KEY FK_2A2B1580A76ED395');
        $this->addSql('DROP TABLE airtel_money');
        $this->addSql('DROP TABLE announcement');
        $this->addSql('DROP TABLE announcement_poster');
        $this->addSql('DROP TABLE answer_to_user_question');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE final_report');
        $this->addSql('DROP TABLE final_report_signature');
        $this->addSql('DROP TABLE incomplete_message');
        $this->addSql('DROP TABLE journal_subscription');
        $this->addSql('DROP TABLE legal_case');
        $this->addSql('DROP TABLE legal_case_category');
        $this->addSql('DROP TABLE legal_case_document');
        $this->addSql('DROP TABLE our_partner');
        $this->addSql('DROP TABLE our_partner_logo');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE rejection_motive');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_activity');
        $this->addSql('DROP TABLE user_details');
        $this->addSql('DROP TABLE user_question');
        $this->addSql('DROP TABLE user_role_choice');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
