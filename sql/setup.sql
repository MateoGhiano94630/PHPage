
DROP DATABASE IF EXISTS contacts_app;

CREATE DATABASE contacts_app;

USE contacts_app;

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    user_id INT NOT NULL,
    phone_number VARCHAR(255),

    FOREIGN KEY (user_id) REFERENCES users(id)
);

--  Creacion de la tabla direcciones con sus respectivos campos
CREATE TABLE adress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag VARCHAR(255),
    street VARCHAR(255),
    num VARCHAR(255),
    departament VARCHAR(255),
    floor VARCHAR(255),
    city VARCHAR(255),
    country_state VARCHAR(255),
    country VARCHAR(255),
    zip INT,
    contact_id INT NOT NULL,
    description VARCHAR(255),
    
    -- Referencia a clave foranea
    FOREIGN KEY (contact_id) REFERENCES contacts(id)
);


insert into users (name, email, password) values ("mateo", "test@test.com","3434321")

insert into contacts (user_id, name, phone_number) values (1, "pepe","430043")
