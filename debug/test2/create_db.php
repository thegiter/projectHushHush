<?php
    if (!defined('root')) {
        define('root', '../../');
    }

    require_once root.'debug/test2/lib/db.php';

    // Create connection
    $conn = new mysqli(Db::HOST, Db::UN, Db::PW);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: ".$conn->connect_error);
    }

    // Create database
    if ($conn->query('CREATE DATABASE '.DB::DB) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: ".$conn->error;
    }

    //create table
    $conn = new mysqli(Db::HOST, Db::UN, Db::PW, Db::DB);

    $conn->query('DROP TABLE IF EXISTS Users');

    if ($conn->query('
        CREATE TABLE Users (
            un VARCHAR(30) NOT NULL PRIMARY KEY,
            pw CHAR(128) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin
    ') === TRUE) {
        echo "Table created successfully";
    } else {
        echo "Error creating table: ".$conn->error;
    }

    //create user, //should have strong pw rules
    if ($conn->query('
        INSERT INTO Users (un, pw)
        VALUES ("Admin", "admin")
    ') === TRUE) {
        echo "user created successfully";
    } else {
        echo "Error: ".$conn->error;
    }
?>
