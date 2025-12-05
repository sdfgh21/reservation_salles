<?php
// create_admin.php
require 'db.php';

$username = 'admin';
$password = 'admin123'; // changer immédiatement
$email = 'admin@example.com';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u");
$stmt->execute(['u'=>$username]);
if ($stmt->fetch()) {
    echo "Admin déjà présent";
    exit;
}

$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:u,:e,:p,'admin')");
$stmt->execute(['u'=>$username,'e'=>$email,'p'=>$hash]);
echo "Admin créé: $username / $password (change-le vite!)";
