<?php
session_start();
require_once "db.php";
require_once "csrf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(!csrf_check($_POST['csrf_token'] ?? '')) {
        $_SESSION["error"] = "Erreur de sécurité CSRF.";
        header("Location: login.php"); exit();
    }
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Vérifier si utilisateur existe
    $sql = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $sql->execute([$email]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION["error"] = "Email introuvable.";
        header("Location: login.php"); exit();
    }
    if (!password_verify($password, $user["password"])) {
        $_SESSION["error"] = "Mot de passe incorrect.";
        header("Location: login.php"); exit();
    }
    // Connexion réussie
    $_SESSION["user"] = [
        "id" => $user["id"],
        "fullname" => $user["fullname"],
        "email" => $user["email"],
        "role" => $user["role"] ?? 'user'
    ];
    header("Location: index.php");
    exit();
}
?>
