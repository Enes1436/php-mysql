<?php


    $host = "localhost";
    $db = "challenge";
    $user = "root";
    $pass = "";


    try{
        $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);


        $sql = "CREATE TABLE category(
        id INT PRIMARY KEY,
        NAME VARCHAR(255) NOT NULL
        );


        CREATE TABLE products(
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price VARCHAR(255) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(id)
        );


        INSERT INTO category(id, name) VALUES
        (1, 'Monitor'),
        (2, 'Keyboard'),
        (3, 'Mouse');



        INSERT INTO products(id, name, price, category_id) VALUES
        (1, 'Monitor1','50$', 1),
        (2, 'Monitor2','50$', 1),
        (3, 'Monitor3','50$', 1),
        (4, 'Monitor4','50$', 1),
        (5, 'Monitor5','50$', 1),
        (6, 'Keyboard1','30$', 2),
        (7, 'Keyboard1','30$', 2),
        (8, 'Keyboard1', '30$',2),
        (9,'Keyboard1', '30$',2),
        (10, 'Keyboard1', '30$',2),
        (11, 'Mouse1','30$', 3),
        (12, 'Mouse1', '30$',3),
        (13, 'Mouse1', '30$',3),
        (14, 'Mouse1', '30$',3),
        (15, 'Mouse1', '30$',3);
        
        ";


        $conn->exec($sql);


        echo("Table created");
        
    }catch(Exception $e){


        echo("Table not created");


    }
?>