-- CRM de Cobranzas - Esquema Base (MySQL compatible)
-- Requisitos cubiertos:
-- - 4 roles (super_admin, admin, coordinador, asesor)
-- - Tipificación para gestión (selector desplegable para asesor)
-- - Reportabilidad para coordinador y administrador incluyendo tipificación
-- - Índices y llaves foráneas

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Crear base de datos si no existe (opcional)
-- CREATE DATABASE IF NOT EXISTS cobranzas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE cobranzas;

-- Tablas de seguridad / organización
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE, -- super_admin, admin, coordinador, asesor
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` INT UNSIGNED NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX `idx_users_role` (`role_id`),
  INDEX `idx_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Relación coordinador -> asesores (equipo)
CREATE TABLE IF NOT EXISTS `coordinator_advisor` (
  `coordinator_id` INT UNSIGNED NOT NULL,
  `advisor_id` INT UNSIGNED NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`coordinator_id`, `advisor_id`),
  CONSTRAINT `fk_team_coord_user` FOREIGN KEY (`coordinator_id`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_team_adv_user` FOREIGN KEY (`advisor_id`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Catálogos
CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tipificaciones: catálogo para selector de resultado de gestión
CREATE TABLE IF NOT EXISTS `typification_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(120) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `typifications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NOT NULL,
  `code` VARCHAR(30) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_typification_code` (`code`),
  CONSTRAINT `fk_typ_category` FOREIGN KEY (`category_id`) REFERENCES `typification_categories`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX `idx_typ_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Deudores y cuentas
CREATE TABLE IF NOT EXISTS `debtors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_type` VARCHAR(20) NULL,
  `document_number` VARCHAR(40) NULL,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NULL,
  `phone_primary` VARCHAR(30) NULL,
  `phone_secondary` VARCHAR(30) NULL,
  `address` VARCHAR(200) NULL,
  `city` VARCHAR(80) NULL,
  `state` VARCHAR(80) NULL,
  `country` VARCHAR(80) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_debtors_doc` (`document_type`, `document_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `debtor_id` INT UNSIGNED NOT NULL,
  `campaign_id` INT UNSIGNED NOT NULL,
  `original_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `current_balance` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `status` VARCHAR(30) NOT NULL DEFAULT 'en_gestion',
  `assigned_advisor_id` INT UNSIGNED NULL,
  `assigned_coordinator_id` INT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_acc_debtor` FOREIGN KEY (`debtor_id`) REFERENCES `debtors`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_acc_campaign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_acc_advisor` FOREIGN KEY (`assigned_advisor_id`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_acc_coordinator` FOREIGN KEY (`assigned_coordinator_id`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX `idx_acc_debtor` (`debtor_id`),
  INDEX `idx_acc_campaign` (`campaign_id`),
  INDEX `idx_acc_assigned` (`assigned_advisor_id`, `assigned_coordinator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Canales de contacto
CREATE TABLE IF NOT EXISTS `contact_channels` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL UNIQUE, -- call, whatsapp, email, sms, letter
  `name` VARCHAR(80) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gestiones (acciones de cobranza) realizadas por asesores
CREATE TABLE IF NOT EXISTS `interactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` INT UNSIGNED NOT NULL,
  `advisor_id` INT UNSIGNED NOT NULL,
  `coordinator_id` INT UNSIGNED NULL, -- denormalizado para reportar/rastrear
  `campaign_id` INT UNSIGNED NOT NULL,
  `channel_id` INT UNSIGNED NOT NULL,
  `typification_id` INT UNSIGNED NOT NULL, -- selector obligatorio
  `notes` TEXT NULL,
  `promise_amount` DECIMAL(12,2) NULL,
  `promise_due_date` DATE NULL,
  `next_contact_at` DATETIME NULL,
  `contacted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_int_account` FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_int_advisor` FOREIGN KEY (`advisor_id`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_int_coordinator` FOREIGN KEY (`coordinator_id`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_int_campaign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_int_channel` FOREIGN KEY (`channel_id`) REFERENCES `contact_channels`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_int_typification` FOREIGN KEY (`typification_id`) REFERENCES `typifications`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX `idx_int_account` (`account_id`),
  INDEX `idx_int_users` (`advisor_id`, `coordinator_id`),
  INDEX `idx_int_campaign` (`campaign_id`),
  INDEX `idx_int_typification` (`typification_id`),
  INDEX `idx_int_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promesas de pago (entidad separada para seguimiento formal)
CREATE TABLE IF NOT EXISTS `payment_promises` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` INT UNSIGNED NOT NULL,
  `interaction_id` BIGINT UNSIGNED NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `due_date` DATE NOT NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'pendiente', -- pendiente, cumplida, incumplida, reprogramada
  `created_by` INT UNSIGNED NOT NULL, -- asesor
  `approved_by` INT UNSIGNED NULL, -- coordinador/admin según umbral
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pp_account` FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_pp_interaction` FOREIGN KEY (`interaction_id`) REFERENCES `interactions`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_pp_created_by` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_pp_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX `idx_pp_account` (`account_id`),
  INDEX `idx_pp_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pagos
CREATE TABLE IF NOT EXISTS `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `payment_date` DATE NOT NULL,
  `external_reference` VARCHAR(100) NULL,
  `source` VARCHAR(50) NULL, -- pasarela, transferencia, caja, etc.
  `reported_by` INT UNSIGNED NULL, -- usuario que reporta (backoffice/asesor)
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pay_account` FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_pay_reported_by` FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX `idx_pay_account` (`account_id`),
  INDEX `idx_pay_date` (`payment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Datos semilla mínimos
INSERT INTO `roles` (`name`, `description`) VALUES
  ('super_admin', 'Control total del sistema'),
  ('admin', 'Administra campañas y reportes globales'),
  ('coordinador', 'Gestiona equipo de asesores'),
  ('asesor', 'Realiza gestiones de cobranza')
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`);

INSERT INTO `contact_channels` (`code`, `name`) VALUES
  ('call', 'Llamada'),
  ('whatsapp', 'WhatsApp'),
  ('email', 'Email'),
  ('sms', 'SMS'),
  ('letter', 'Carta')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Tipificaciones semilla (ejemplo)
INSERT INTO `typification_categories` (`code`, `name`) VALUES
  ('contacto', 'Contacto'),
  ('no_contacto', 'No Contacto'),
  ('promesa', 'Promesa de Pago'),
  ('pago', 'Pago'),
  ('incidencia', 'Incidencia')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Asegura IDs de categorías para las inserciones siguientes
-- (En entornos reales se haría por código, aquí usamos SELECTs en aplicación.)

INSERT INTO `typifications` (`category_id`, `code`, `name`)
SELECT c.id, 'contacto_efectivo', 'Contacto Efectivo'
FROM typification_categories c WHERE c.code = 'contacto'
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `typifications` (`category_id`, `code`, `name`)
SELECT c.id, 'buzon_voz', 'Buzón de voz'
FROM typification_categories c WHERE c.code = 'no_contacto'
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `typifications` (`category_id`, `code`, `name`)
SELECT c.id, 'promesa_simple', 'Promesa Simple'
FROM typification_categories c WHERE c.code = 'promesa'
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `typifications` (`category_id`, `code`, `name`)
SELECT c.id, 'pago_reportado', 'Pago Reportado'
FROM typification_categories c WHERE c.code = 'pago'
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `typifications` (`category_id`, `code`, `name`)
SELECT c.id, 'datos_incorrectos', 'Datos Incorrectos'
FROM typification_categories c WHERE c.code = 'incidencia'
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Vistas de reporte (incluyen tipificación)
-- Vista para Coordinador: interacciones de sus asesores, con detalle
CREATE OR REPLACE VIEW `vw_coordinator_interactions` AS
SELECT
  i.id AS interaction_id,
  i.created_at,
  i.campaign_id,
  ca.name AS campaign_name,
  i.coordinator_id,
  coord.full_name AS coordinator_name,
  i.advisor_id,
  adv.full_name AS advisor_name,
  i.account_id,
  d.full_name AS debtor_name,
  ch.code AS channel_code,
  t.code AS typification_code,
  t.name AS typification_name,
  i.contacted,
  i.promise_amount,
  i.promise_due_date,
  i.next_contact_at
FROM interactions i
JOIN accounts a ON a.id = i.account_id
JOIN debtors d ON d.id = a.debtor_id
JOIN campaigns ca ON ca.id = i.campaign_id
JOIN users adv ON adv.id = i.advisor_id
LEFT JOIN users coord ON coord.id = i.coordinator_id
JOIN contact_channels ch ON ch.id = i.channel_id
JOIN typifications t ON t.id = i.typification_id;

-- Vista para Administrador: cobertura global
CREATE OR REPLACE VIEW `vw_admin_interactions` AS
SELECT
  i.id AS interaction_id,
  i.created_at,
  ca.id AS campaign_id,
  ca.name AS campaign_name,
  i.coordinator_id,
  coord.full_name AS coordinator_name,
  i.advisor_id,
  adv.full_name AS advisor_name,
  i.account_id,
  d.full_name AS debtor_name,
  ch.code AS channel_code,
  t.code AS typification_code,
  t.name AS typification_name,
  i.contacted,
  i.promise_amount,
  i.promise_due_date,
  i.next_contact_at
FROM interactions i
JOIN accounts a ON a.id = i.account_id
JOIN debtors d ON d.id = a.debtor_id
JOIN campaigns ca ON ca.id = i.campaign_id
JOIN users adv ON adv.id = i.advisor_id
LEFT JOIN users coord ON coord.id = i.coordinator_id
JOIN contact_channels ch ON ch.id = i.channel_id
JOIN typifications t ON t.id = i.typification_id;

-- Vistas agregadas sugeridas (rápidas para KPIs)
CREATE OR REPLACE VIEW `vw_kpi_coordinator_daily` AS
SELECT
  DATE(i.created_at) AS kpi_date,
  i.campaign_id,
  i.coordinator_id,
  COUNT(*) AS total_gestiones,
  SUM(i.contacted = 1) AS contactos,
  SUM(CASE WHEN t.code LIKE 'promesa%' THEN 1 ELSE 0 END) AS promesas,
  SUM(CASE WHEN t.code = 'pago_reportado' THEN 1 ELSE 0 END) AS pagos_reportados
FROM interactions i
JOIN typifications t ON t.id = i.typification_id
GROUP BY DATE(i.created_at), i.campaign_id, i.coordinator_id;

CREATE OR REPLACE VIEW `vw_kpi_admin_daily` AS
SELECT
  DATE(i.created_at) AS kpi_date,
  i.campaign_id,
  COUNT(*) AS total_gestiones,
  SUM(i.contacted = 1) AS contactos,
  SUM(CASE WHEN t.code LIKE 'promesa%' THEN 1 ELSE 0 END) AS promesas,
  SUM(CASE WHEN t.code = 'pago_reportado' THEN 1 ELSE 0 END) AS pagos_reportados
FROM interactions i
JOIN typifications t ON t.id = i.typification_id
GROUP BY DATE(i.created_at), i.campaign_id;


