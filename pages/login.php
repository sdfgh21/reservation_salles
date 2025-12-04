<?php
session_start();
require 'db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute(['u'=>$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        // stocker l'essentiel dans la session
        $_SESSION['user'] = ['id'=>$user['id'],'username'=>$user['username'],'role'=>$user['role']];
        header("Location: index.php");
        exit;
    } else {
        $error = "Identifiants invalides.";
    }
}
?>
<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h1>Connexion</h1>
  <?php if($error) echo "<p class='error'>$error</p>"; ?>
  <form method="post">
    <label>Nom d'utilisateur</label><input name="username" required>
    <label>Mot de passe</label><input name="password" type="password" required>
    <button type="submit">Se connecter</button>
  </form>
  <p><a href="register.php">Créer un compte</a></p>
</div>
</body></html>
