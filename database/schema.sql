-- Pharmadesk Helpdesk Apotek - Skema MySQL (Ringkas)

CREATE TABLE roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

ALTER TABLE users
  ADD COLUMN role_id BIGINT UNSIGNED NULL AFTER id,
  ADD CONSTRAINT fk_users_role_id FOREIGN KEY (role_id) REFERENCES roles(id);

CREATE TABLE pharmacies (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  pic_name VARCHAR(255) NULL,
  phone VARCHAR(50) NULL,
  whatsapp VARCHAR(50) NULL,
  address TEXT NULL,
  city VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE ticket_modules (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE ticket_categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE tickets (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pharmacy_id BIGINT UNSIGNED NOT NULL,
  customer_id BIGINT UNSIGNED NULL,
  expert_id BIGINT UNSIGNED NULL,
  tech_id BIGINT UNSIGNED NULL,
  module_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  app_version VARCHAR(50) NULL,
  priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  status ENUM('open','in_progress','for_review','closed') NOT NULL DEFAULT 'open',
  source ENUM('team_expert','internal') NOT NULL DEFAULT 'team_expert',
  opened_at DATETIME NOT NULL,
  closed_at DATETIME NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_tickets_pharmacy_id FOREIGN KEY (pharmacy_id) REFERENCES pharmacies(id),
  CONSTRAINT fk_tickets_customer_id FOREIGN KEY (customer_id) REFERENCES users(id),
  CONSTRAINT fk_tickets_expert_id FOREIGN KEY (expert_id) REFERENCES users(id),
  CONSTRAINT fk_tickets_tech_id FOREIGN KEY (tech_id) REFERENCES users(id),
  CONSTRAINT fk_tickets_module_id FOREIGN KEY (module_id) REFERENCES ticket_modules(id),
  CONSTRAINT fk_tickets_category_id FOREIGN KEY (category_id) REFERENCES ticket_categories(id)
);

CREATE TABLE ticket_activities (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  old_status ENUM('open','in_progress','for_review','closed') NULL,
  new_status ENUM('open','in_progress','for_review','closed') NULL,
  note TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_activities_ticket_id FOREIGN KEY (ticket_id) REFERENCES tickets(id),
  CONSTRAINT fk_activities_user_id FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE ticket_attachments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  filename VARCHAR(255) NOT NULL,
  path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) NULL,
  size BIGINT UNSIGNED NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_attachments_ticket_id FOREIGN KEY (ticket_id) REFERENCES tickets(id),
  CONSTRAINT fk_attachments_user_id FOREIGN KEY (user_id) REFERENCES users(id)
);
