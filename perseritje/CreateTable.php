<?php
try {

    $pdo = new PDO("mysql:host=localhost;dbname=Auto Sallon", "root", "");

    

    // $sql = "ALTER TABLE makina DROP column VP";
     
    $sql = "INSERT INTO makina (Garancioni,Motorri) VALUES (20,20)";

    $pdo->exec($sql);

    echo "Column created successfully!";

} catch (PDOException $e) {
    echo "Error creating column: " . $e->getMessage();
}
?>
