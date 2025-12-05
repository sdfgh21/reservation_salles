<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #1cc88a, #4e73df);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 18px;
            box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
            width: 380px;
            text-align: center;
        }

        .register-container h2 {
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
            border-color: #1cc88a;
            box-shadow: 0 0 4px rgba(28,200,138,0.5);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: #4e73df;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-register:hover {
            background: #4e73df
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
            background: #159f6b;
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

<div class="register-container">
    <h2>Créer un compte</h2>

    <form action="register_process.php" method="POST">

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

        <button type="submit" class="btn-register">Créer le compte</button>
    </form>

    <div class="links">
        <a href="login.php">Déjà un compte ? Se connecter</a>
    </div>

    <a href="index.php" class="back-btn">⬅ Retour à la page principale</a>

</div>

</body>
</html>
