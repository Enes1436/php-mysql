<?php
$host = "localhost";
$db = "testenes";
$user = "root";
$pass = "";


try{
    $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);

$sql = "CREATE TABLE users(
    id INT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE category(
        id INT PRIMARY KEY,
        NAME VARCHAR(255) NOT NULL
        );
        
CREATE TABLE products(
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(id)
        );   
        
      INSERT INTO  category (id, name) VALUES 
        (1,'Fruta' ),
        (2,'Bakery' ),
        (3,'Fast Food');
        
        
        
INSERT INTO products (id, name, category_id) VALUES
        (1,'Molla', 1),
        (2,'Torte', 2),
        (3,'Pizza', 3),
        (4,'Dardha', 1),
        (5,'Hamburger', 3);
        
         ";

$conn->exec($sql);

    echo" Table created ";
}catch(Exception $e){
    echo(" Table not created" );
}

?>