<?php
try{
     $pdo = new PDO("mysql:host=localhost;dbname=Auto Sallon", "root", "");

    $sql ="Drop table makina";

    

    $pdo->exec($sql);

    echo "Table droped successfully!";

} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}
?>