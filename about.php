<?php

session_start();
include 'db-conn.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

$userId = $_SESSION['user_id'];
$query = "SELECT photo_profil FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$photoProfil = $user && $user['photo_profil'] ? $user['photo_profil'] : 'default_profile.png';

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
</head>
<body>
    <style>
        
        .about-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .about-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 46%; 
        }

        .about-logo {
            width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .about-box h1 {
            font-size: 2em;
            margin-bottom: 15px;
            color: #333;
        }

        .about-description {
            font-size: 1.2em;
            color: #555;
            line-height: 1.6;
        }

    </style>
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
        <img src="./uploads/<?= htmlspecialchars($photoProfil); ?>" alt="Profile" class="rounded-circle" width="30" height="30">
    </button>
    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="modify_profile.php">Modifier Profil</a></li>
        <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
    </ul>
</div>

</div>

</header>

<div class="about-container">
        <div class="about-box">
            <img src="./img/logo-sbee.png" alt="Logo Intern Software" class="about-logo">
            <h1>Intern Software</h1>
            <p class="about-description">
                Intern Software a été créé dans le but de fournir une plateforme permettant aux entreprises de gérer efficacement leurs logiciels internes.
                Ce projet vise à simplifier la gestion des logiciels, offrant une interface intuitive pour suivre, ajouter, et gérer les différents outils utilisés en interne. 
                Depuis son déploiement en [Date de déploiement], il a aidé de nombreuses entreprises à mieux organiser leurs ressources technologiques.
            </p>
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
    <script>
        function redirectToAddSoftware() {
            window.location.href = 'add-software.php';
        }

    </script>
</body>
</html>
