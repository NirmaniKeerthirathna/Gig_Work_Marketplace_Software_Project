CREATE DATABASE User_Database;

CREATE TABLE users (
  user_ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  email VARCHAR(100) NOT NULL,  
  password varchar(100) NOT NULL
);