<?php
    include_once('config.php');

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];

        $sql = "insert into user(name, username, email) values (:name, :username, :email)";
        $sqlQuery = $conn->prepare($sql);

        $sqlQuery->bindParam(':name',$name);
        $sqlQuery->bindParam(':username',$username);
        $sqlQuery->bindParam(':email', $email);

        $sqlQuery->execute();

        echo "User added successfully ...<br>";
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="dashboard.php">Dashboard</a>
    <form action="add.php" method="POST">
        <input type="text" name="name" placeholder="Name..."><br>
        <input type="text" name="username" pla3ceholder="Username..."><br>
        <input type="email" name="email" placeholder="Email..."><br>
        <button type="submit" name="submit">Add </button>
    </form>    




</body>
</html>