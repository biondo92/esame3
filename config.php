<?php
const SERVER = "localhost";
const USERNAME = "root";
const PASS = "";
const DB = "esercitazione3";

const INIT_DB_QUERY = "create database if not exists " . DB;
const CREATE_TABLE_USERS = "create table users (
    id int not null auto_increment,
    email varchar(96) not null,
    pass varchar(96) not null,
    primary key(id)
)";
const CREATE_TABLE_CATEGORY = "create table category (
    id int not null auto_increment,
    name varchar(96) not null,
    primary key(id)
)";
const CREATE_TABLE_PROJECTS = "create table projects (
    id int not null auto_increment,
    name varchar(96) not null,
    description varchar(250),
    categoryId int not null,
    image varchar(max),
    primary key(id), 
    FOREIGN KEY (categoryId) REFERENCES category(id)
)";
const INSERT_USERS = "insert into users (email,pass) 
values ('admin@gmail.com', 'michele')";
const INSERT_CATEGORY = "insert into category (name)
values ('sviluppo web'),('sviluppo software'),
('web design')";
