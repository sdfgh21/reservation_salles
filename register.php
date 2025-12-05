<?php
session_start();
require_once "csrf.php";
$msg = '';
if(isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    unset($_SESSION['error']);
}
if(isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="register-container">
    <h2>Créer un compte</h2>
    <?php if($msg): ?><div class="error"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form action="register_process.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
        <div class="input-group">
            <label>Nom complet</label>
            <input type="text" name="fullname" required>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <div class="input-group">
            <label>Confirmation du mot de passe</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn-register">Créer le compte</button>
    </form>
    <div class="links">
        <a href="login.php">Déjà un compte ? Se connecter</a>
    </div>
    <a href="index.php" class="back-btn">⬅ Retour à la page principale</a>
</div>
</body>
</html>
