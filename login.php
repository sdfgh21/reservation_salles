<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 18px;
            box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
            width: 360px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .input-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .input-group label {
            font-size: 14px;
            color: #444;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-top: 5px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 4px rgba(78,115,223,0.5);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #4e73df;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: .3s;
        }

        .btn-login:hover {
            background: #3752c6;
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            color: #4e73df;
            text-decoration: none;
            font-size: 14px;
            transition: .3s;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #1cc88a;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            transition: .3s;
        }

        .back-btn:hover {
            background: #159f6b;
        }
    </style>
</head>

<body>

<div class="login-container">
  
    <h2>Connexion</h2>

    <form action="login_process.php" method="POST">

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
