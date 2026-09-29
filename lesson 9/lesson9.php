<?php

    $username = $_GET['username'];

    $password = $_GET['password'];

    echo $username;
    echo '<br>';
    echo $password;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <form action="lesson9.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" placeholder="Username"><br>
        <label for="password">Password:</label><br>
        <input type="passord" id="password" name="password" placeholder="Password"><br><br>
        <input type="submit" value="Submit">
    </form>


</body>
</html>