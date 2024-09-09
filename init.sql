CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  public_id varchar(36) UNIQUE NOT NULL,
  email varchar(50) UNIQUE NOT NULL,
  password varchar(64) NOT NULL,
  salt varchar(64) UNIQUE NOT NULL,
  name varchar(50) NOT NULL,
  birthdate date NOT NULL,
  city varchar(50) DEFAULT NULL,
  work varchar(50) DEFAULT NULL,
  avatar varchar(100) NOT NULL DEFAULT 'default.jpg',
  cover varchar(100) NOT NULL DEFAULT 'default.jpg'
);
