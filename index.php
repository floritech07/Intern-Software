<?php

include 'db-conn.php'; 

session_start();


if (isset($_SESSION['user_id'])) {
    header('location:home.php');
    exit();
}

$message = '';
$redirect = false;

if (isset($_POST['register'])) { 
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);  

    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $message = 'Utilisateur déjà existant avec cet email!';
    } else {
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert = $stmt->execute([$username, $email, $pass]);

        if ($insert) {
            $message = 'Inscription réussie! Connectez-vous maintenant.';
            $redirect = true;  
        } else {
            $message = 'Échec de l\'inscription.';
        }
    }
}

if (isset($_POST['login'])) { 
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $message = 'Connexion réussie! Bienvenue ' . $user['username'];
        $redirect = true; 
    } else {
        $message = 'Email ou mot de passe incorrect!';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/logo-sbee.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./css/sign.css">
    <title> SBEE | Intern Software</title>
    <script>
       
        function showAlert(message, redirect) {
            alert(message);
            if (redirect) {
                window.location.href = 'index.php'; 
            }
        }
    </script>
</head>

<style>
    .span1 {
        text-align: center;
        justify-content: center;
    }
</style>

<body>

    <?php
    if (!empty($message)) {
        echo "<script>showAlert('$message', " . ($redirect ? 'true' : 'false') . ");</script>";
    }
    ?>

    <div class="container" id="container">
        
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Créer un Compte</h1>
                <br>
                <span class="span1">Utilisez votre adresse email et créez un mot de passe pour vous inscrire.</span>
                <br>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="email" name="email" placeholder="Adresse Email" required>
                <input type="password" name="password" placeholder="Mot de Passe" required>
                <button type="submit" name="register">S'inscrire</button>
            </form>
        </div>

        
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Se Connecter</h1>
                <br>
                <span class="span1">Utilisez votre adresse email et votre mot de passe pour vous connecter</span>
                <br>
                <input type="email" name="email" placeholder="Adresse Email" required>
                <input type="password" name="password" placeholder="Mot de Passe" required>
                <a href="#">Mot de Passe oublié?</a>
                <button type="submit" name="login">Se Connecter</button>
            </form>
        </div>

        
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Ah! Vous revoilà.</h1>
                    <p>Entrez vos informations personnelles afin de vous connecter</p>
                    <button class="hidden" id="login">Connectez-Vous</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bienvenue!</h1>
                    <p>Veuillez créer un compte pour accéder à Intern Software</p>
                    <button class="hidden" id="register">Inscrivez-Vous</button>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/sign.js"></script>
</body>

</html>
