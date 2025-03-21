CREATE DATABASE IF NOT EXISTS test;

USE test;

CREATE TABLE IF NOT EXISTS categories (
    name VARCHAR(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    inStock BOOLEAN NOT NULL,
    description TEXT,
    category VARCHAR(255),
    brand VARCHAR(255),
    FOREIGN KEY (category) REFERENCES categories(name)
);

CREATE TABLE IF NOT EXISTS galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255),
    image_url VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS attributes (
    id VARCHAR(255) PRIMARY KEY,
    displayValue VARCHAR(255),
    value VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS attribute_sets (
    attribute_set_id INT AUTO_INCREMENT PRIMARY KEY,
    id VARCHAR(255),
    product_id VARCHAR(255),
    name VARCHAR(255),
    type VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS attributes_attribute_sets (
    attribute_set_id INT,
    attribute_id VARCHAR(255),
    PRIMARY KEY (attribute_set_id, attribute_id),
    FOREIGN KEY (attribute_set_id) REFERENCES attribute_sets(attribute_set_id),
    FOREIGN KEY (attribute_id) REFERENCES attributes(id)
);


CREATE TABLE IF NOT EXISTS prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255),
    amount DECIMAL(10, 2),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS currencies (
    price_id INT PRIMARY KEY,
    label VARCHAR(255),
    symbol VARCHAR(255),
    FOREIGN KEY (price_id) REFERENCES prices(id)
);