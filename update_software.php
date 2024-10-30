<?php
include 'db-conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $logiciel = $pdo->prepare("SELECT * FROM logiciels WHERE id = :id");
    $logiciel->execute([':id' => $id]);
    $logiciel = $logiciel->fetch(PDO::FETCH_ASSOC);

    if ($logiciel === false) {
        echo "Logiciel introuvable.";
        exit; 
    }

    $nomLogiciel = isset($logiciel['nom']) ? htmlspecialchars($logiciel['nom']) : '';
    $descriptionLogiciel = isset($logiciel['description']) ? htmlspecialchars($logiciel['description']) : '';
    $lienLogiciel = isset($logiciel['lien']) ? htmlspecialchars($logiciel['lien']) : '';
} else {
    echo "Aucun identifiant de logiciel fourni.";
    exit; 
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
        header {
            margin-bottom: 20px;
        }
        footer {
            margin-top: 20px;
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
    <h2 class="text-center">Mettre à jour le Logiciel</h2>
    <form action="process_update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <div class="form-group">
            <label for="nom">Nom du logiciel :</label>
            <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $nomLogiciel; ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" id="description" class="form-control" required><?php echo $descriptionLogiciel; ?></textarea>
        </div>

        <div class="form-group">
            <label for="lien">Lien du logiciel :</label>
            <input type="url" name="lien" id="lien" class="form-control" value="<?php echo $lienLogiciel; ?>" required>
        </div>

        <div class="text-center">
            <input type="submit" value="Mettre à jour" class="btn btn-primary">
        </div>
    </form>
</div>

<footer class="py-3">
    <div class="container">
        <p class="m-0">© 2024 SBEE. Tous droits réservés. | <a href="#">Politique de confidentialité</a></p>
    </div>
</footer>

</body>
</html>
