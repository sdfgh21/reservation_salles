<?php
session_start();
require 'db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $password === '') $error = "Nom et mot de passe requis.";
    elseif ($password !== $password2) $error = "Les mots de passe ne correspondent pas.";
    else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u");
        $stmt->execute(['u'=>$username]);
        if ($stmt->fetch()) $error = "Nom déjà utilisé.";
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash) VALUES (:u,:e,:p)");
            $stmt->execute(['u'=>$username,'e'=>$email,'p'=>$hash]);
            $_SESSION['user'] = ['id'=>$pdo->lastInsertId(),'username'=>$username,'role'=>'prof'];
            header("Location: index.php");
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><title>Inscription</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h1>Inscription</h1>
  <?php if($error) echo "<p class='error'>$error</p>"; ?>
  <form method="post">
    <label>Nom d'utilisateur</label><input name="username" required>
    <label>Email (optionnel)</label><input name="email" type="email">
    <label>Mot de passe</label><input name="password" type="password" required>
    <label>Confirmer mot de passe</label><input name="password2" type="password" required>
    <button type="submit">S'inscrire</button>
  </form>
  <p><a href="login.php">Déjà un compte ? Se connecter</a></p>
</div>
</body></html>
