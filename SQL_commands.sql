CREATE DATABASE library;

USE library;

CREATE TABLE authors (id serial PRIMARY KEY, first_name VARCHAR (255), last_name VARCHAR (255));

CREATE TABLE books (id serial PRIMARY KEY, title VARCHAR (255));

CREATE TABLE books_authors (id serial PRIMARY KEY, author_id INT, book_id INT);

CREATE TABLE copies (id serial Primary KEY, number_of VARCHAR (255), book_id INT, checked_out BOOLEAN);

CREATE TABLE patrons (id serial PRIMARY KEY, name VARCHAR (255), email_address VARCHAR (255));

CREATE TABLE checkouts (id serial PRIMARY KEY, copies_id INT, patrons_id INT, due_date DATE);
