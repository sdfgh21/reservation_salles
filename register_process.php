<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Vérifier si email existe déjà
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $_SESSION["error"] = "Cet email est déjà utilisé.";
        header("Location: register.php");
        exit();
    }

    // Hash du mot de passe
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Enregistrer dans la base
    $sql = $pdo->prepare("
        INSERT INTO users (fullname, email, password)
        VALUES (?, ?, ?)
    ");

    if ($sql->execute([$fullname, $email, $hashed])) {
        $_SESSION["success"] = "Compte créé avec succès ! Vous pouvez vous connecter.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION["error"] = "Erreur lors de la création du compte.";
        header("Location: register.php");
        exit();
    }
}
?>
