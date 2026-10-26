-- Creates the database and the tasks table.
-- Equivalent to the Laravel migration in backend/database/migrations.

CREATE DATABASE IF NOT EXISTS task_list
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE task_list;

CREATE TABLE IF NOT EXISTS tasks (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title      VARCHAR(100)    NOT NULL,
  completed  BOOLEAN         NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP       NULL,
  updated_at TIMESTAMP       NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;