<?php
session_start();
require_once "csrf.php";
$msg = '';
if(isset($_SESSION['error'])) { $msg = $_SESSION['error']; unset($_SESSION['error']); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-container">
    <h2>Connexion</h2>
    <?php if($msg): ?><div class="error"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form action="login_process.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Se connecter</button>
    </form>
    <div class="links">
        <a href="register.php">Créer un compte</a>
    </div>
    <a href="index.php" class="back-btn">⬅ Retour à la page principale</a>
</div>
</body>
</html>
