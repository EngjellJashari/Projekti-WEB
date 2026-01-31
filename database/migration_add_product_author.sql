-- Migration: add author_id to products (run manually)
ALTER TABLE products ADD COLUMN author_id INT NULL AFTER stock;
ALTER TABLE products ADD COLUMN image VARCHAR(255) NULL AFTER price; -- in case missing
ALTER TABLE products ADD CONSTRAINT fk_products_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL;

-- note: run this migration in your DB (phpMyAdmin or CLI) BEFORE using the admin product author features.
