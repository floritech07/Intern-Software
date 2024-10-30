<?php
include 'db-conn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vous devez être connecté pour ajouter un logiciel.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['softwareName']) || empty($_POST['description']) || empty($_POST['link']) || empty($_FILES['image']['name'])) {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $softwareName = htmlspecialchars($_POST['softwareName']);
    $description = htmlspecialchars($_POST['description']);
    $link = htmlspecialchars($_POST['link']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageName = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $imageName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('Le fichier n\'est pas une image.');</script>";
            exit();
        }

        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_extensions)) {
            echo "<script>alert('Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.');</script>";
            exit();
        }

        if ($_FILES["image"]["size"] > 2000000) {
            echo "<script>alert('L\'image est trop volumineuse. Taille maximale : 2MB.');</script>";
            exit();
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO logiciels (nom, image, description, lien, user_id) 
                    VALUES (:softwareName, :image, :description, :link, :user_id)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':softwareName', $softwareName);
            $stmt->bindParam(':image', $target_file);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Nouveau logiciel ajouté avec succès.'); window.location.href='home.php';</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'ajout du logiciel.');</script>";
            }
        } else {
            echo "<script>alert('Erreur lors du téléchargement de l\'image.');</script>";
        }
    } else {
        echo "<script>alert('Veuillez choisir une image valide.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBEE | Intern_software</title>
    <link rel="shortcut icon" href="./img/logo-sbee.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background-color: #f9f9f9;
        }
        header{
            margin-bottom:20px;
        }
        footer{
            margin-top:20px;
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

    <form class="d-none d-md-flex align-items-center">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Rechercher..." aria-label="Search">
            <button class="btn btn-outline-light" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <div class="d-flex align-items-center">
        <button id="dark-mode-toggle" class="btn btn-light">
            <i id="dark-mode-icon" class="bi bi-moon-fill"></i>
        </button>

        <div class="dropdown ms-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-fill"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="#">Modifier Profil</a></li>
                <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="form-container">
    <h2 class="text-center">Ajouter un nouveau Logiciel</h2>
    <form id="softwareForm" action="add-software.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="softwareName">Nom du logiciel :</label>
            <input type="text" class="form-control" id="softwareName" name="softwareName" required>
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="link">Lien du logiciel :</label>
            <input type="url" class="form-control" id="link" name="link" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Confirmer l'ajout du logiciel</button>
            <button type="reset" class="btn btn-secondary">Réinitialiser les champs</button>
        </div>
    </form>
</div>

<footer class="py-3">
    <div class="container">
        <p class="m-0">© 2024 SBEE. Tous droits réservés. | <a href="#">Politique de confidentialité</a></p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
