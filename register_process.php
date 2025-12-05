<?php
session_start();
require_once "db.php";
require_once "csrf.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF check
    if(!csrf_check($_POST['csrf_token'] ?? '')) {
        $_SESSION["error"] = "Erreur de sécurité CSRF.";
        header("Location: register.php"); exit();
    }

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"]; 
    $confirm = $_POST["confirm_password"];

    if ($password !== $confirm) {
        $_SESSION["error"] = "Les mots de passe ne correspondent pas.";
        header("Location: register.php"); exit();
    }
    if(strlen($password) < 8 || // Strong password check
        !preg_match('@[A-Z]@', $password) ||
        !preg_match('@[a-z]@', $password) ||
        !preg_match('@[0-9]@', $password)) {
        $_SESSION["error"] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
        header("Location: register.php"); exit();
    }
    // Vérifier si email existe déjà
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        $_SESSION["error"] = "Cet email est déjà utilisé.";
        header("Location: register.php"); exit();
    }
    // Enregistrement
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, 'user')");
    if ($sql->execute([$fullname, $email, $hashed])) {
        $_SESSION["success"] = "Compte créé avec succès ! Vous pouvez vous connecter.";
        header("Location: login.php"); exit();
    } else {
        $_SESSION["error"] = "Erreur lors de la création du compte.";
        header("Location: register.php"); exit();
    }
}
?>
