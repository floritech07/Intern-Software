<?php
include 'db-conn.php';
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $username = $_POST['username'];

    
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'uploads/'; 
        $targetFile = $targetDir . basename($_FILES['photo_profil']['name']);
        
       
        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $targetFile)) {
            $photo_profil = $targetFile;  
        } else {
            $photo_profil = $_SESSION['user_photo']; 
        }
    } else {
        $photo_profil = $_SESSION['user_photo'];
    }

   
    $stmt = $pdo->prepare("UPDATE users SET email = ?, username = ?, photo_profil = ? WHERE id = ?");
    $stmt->execute([$email, $username, $photo_profil, $_SESSION['user_id']]);

    
    $_SESSION['username'] = $username;
    $_SESSION['user_photo'] = $photo_profil;

    
    echo "<script>alert('Profil mis à jour avec succès!'); window.location.href = 'home.php';</script>";
}


if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Veuillez vous connecter pour modifier votre profil.'); window.location.href = 'login.php';</script>";
    exit; 
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBEE | Intern-Software</title>
    <link rel="shortcut icon" href="./img/logo-sbee.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/slide.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .form-container {
        max-width: 600px; 
        margin: 0 auto; 
        padding: 60px 40px; 
        border: 1px solid #007bff; 
        border-radius: 10px;
        background-color: #f8f9fa; 
        min-height: 400px; 
    }
        footer{
            margin-top:20px;
        }
        .maj{
           align-items:center; 
           text-align:center;
           justify-content:center;
        }
    </style>
</head>
<body>
<header class="d-flex align-items-center justify-content-between p-3 shadow" style="background-color: #007bff;">
        <div class="d-flex align-items-center">
            <img src="./img/logo-sbee.png" alt="Logo" class="logo me-2">
            <span class="app-name text-white"> SBEE | Intern-Software </span>
        </div>

        <nav class="d-none d-md-flex">
            <a href="about.php" class="nav-link text-white me-4">À propos</a>
            <a href="documentation.php" class="nav-link text-white me-4">Documentation</a>
            <a href="maj.php" class="nav-link text-white">Mise à jour</a>
        </nav>

        <form class="d-none d-md-flex align-items-center" id="searchForm" onsubmit="return searchSoftware(event)">
        <div class="input-group">
        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher un logiciel..." aria-label="Search">
        <button class="btn btn-outline-light" type="submit">
        <i class="bi bi-search"></i>
        </button>
        </div>
        </form>


        <div class="d-flex align-items-center">
            <button id="dark-mode-toggle" class="btn btn-light">
                <i id="dark-mode-icon" class="bi bi-moon-fill"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle ms-2" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-fill"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="#">Modifier Profil</a></li>
                    <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
                </ul>
            </div>
        </div>
    </header>

<div class="container mt-5">
    
    <div class="form-container">
        <form action="modify_profile.php" method="POST" enctype="multipart/form-data">
        <h2 class="text-center">Modifier votre profil</h2>
            <div class="mb-3">
                <label for="photo_profil" class="form-label">Changer la photo de profil</label>
                <input type="file" class="form-control" id="photo_profil" name="photo_profil" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" required>
            </div>
            <div class="maj">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<footer class="py-3">
    <div class="container">
        <p class="m-0">© 2024 SBEE. Tous droits réservés. | <a href="#">Politique de confidentialité</a></p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="./js/script.js"></script>
</body>
</html>
