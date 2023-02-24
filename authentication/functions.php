<?php

function connectDb()
{
    try {
        $conn = new PDO("mysql:host=127.0.0.1:8889;dbname=authentication", 'test', 'test');
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function getUserPassword($email) {
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE email = "' . $email . '"';
    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}


function logUser($email, $password)
{
    $connexion = connectDb();
    $sql = $connexion->prepare('SELECT * FROM users WHERE email = ?');
    $sql->bindParam(1, $email);
    $sql->execute();

    $user = $sql->fetch(PDO::FETCH_ASSOC);
    if(password_verify($password,$user['password'])) {
        return $user;
    } else {
        return null;
    };
    
}

function getUser($id) {
    $connexion = connectDb();
    $sql = $connexion->prepare('SELECT * FROM users WHERE id = ?');
    $sql->bindParam(1, $id);
    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_OBJ);
}

function saveUser($email, $username, $password) {
    $connexion = connectDb();
    $sql = 'INSERT INTO users(username,email,password) VALUES("'.htmlentities($email).'","'.htmlentities($username).'","'.password_hash($password, PASSWORD_DEFAULT).'")';
    $stmt = $connexion->prepare($sql);

    return $stmt->execute();
}