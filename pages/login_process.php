<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Vérifier si utilisateur existe
    $sql = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $sql->execute([$email]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION["error"] = "Email introuvable.";
        header("Location: login.php");
        exit();
    }

    // Vérifier mot de passe
    if (!password_verify($password, $user["password"])) {
        $_SESSION["error"] = "Mot de passe incorrect.";
        header("Location: login.php");
        exit();
    }

    // Connexion réussie → créer session
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["fullname"] = $user["fullname"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["logged"] = false;

    // Redirection vers page d’accueil
    header("Location: index.php");
    exit();
}
?>
