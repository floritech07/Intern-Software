<?php

session_start();
include 'db-conn.php';


$query = "SELECT id, nom FROM logiciels"; 
$stmt = $pdo->prepare($query);
$stmt->execute();
$logiciels = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_logiciel = $_POST['id_logiciel'];
    $version = $_POST['version'];
    $fonctionnalites = $_POST['fonctionnalites'];

    $insertQuery = "INSERT INTO mises_a_jour (id_logiciel, version, fonctionnalites) VALUES (:id_logiciel, :version, :fonctionnalites)";
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->execute([
        'id_logiciel' => $id_logiciel,
        'version' => $version,
        'fonctionnalites' => $fonctionnalites,
    ]);

    
    header("Location: maj.php");
    exit();
}


$mises_a_jour = [];
foreach ($logiciels as $logiciel) {
    $updateQuery = "SELECT version, fonctionnalites, date_maj FROM mises_a_jour WHERE id_logiciel = :id_logiciel";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(['id_logiciel' => $logiciel['id']]);
    $mises_a_jour[$logiciel['id']] = $updateStmt->fetchAll(PDO::FETCH_ASSOC);
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
                <img src="./uploads/<?= htmlspecialchars($photoProfil); ?>" alt="Profile" class="rounded-circle" width="30" height="30">
            </button>
            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="modify_profile.php">Modifier Profil</a></li>
                <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="container mt-4">
    <h1 class="text-center">Mises à Jour des Logiciels</h1>

    <div class="border rounded p-4 mx-auto" style="max-width: 600px;">
        <form method="post">
            <div class="mb-3">
                <label for="id_logiciel" class="form-label">Sélectionner le Logiciel :</label>
                <select id="id_logiciel" name="id_logiciel" class="form-select" required>
                    <?php foreach ($logiciels as $logiciel) : ?>
                        <option value="<?= $logiciel['id']; ?>"><?= htmlspecialchars($logiciel['nom']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="version" class="form-label">Nouvelle Version :</label>
                <input type="text" id="version" name="version" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fonctionnalites" class="form-label">Fonctionnalités :</label>
                <textarea id="fonctionnalites" name="fonctionnalites" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter Mise à Jour</button>
        </form>
    </div>

    <h2 class="mt-5">Mises à Jour Existantes</h2>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Logiciel</th>
                <th>Version</th>
                <th>Fonctionnalités</th>
                <th>Date de Mise à Jour</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logiciels as $logiciel) : ?>
                <?php foreach ($mises_a_jour[$logiciel['id']] as $maj) : ?>
                    <tr>
                        <td><?= htmlspecialchars($logiciel['nom']); ?></td>
                        <td><?= htmlspecialchars($maj['version']); ?></td>
                        <td><?= htmlspecialchars($maj['fonctionnalites']); ?></td>
                        <td><?= htmlspecialchars($maj['date_maj']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer class="py-3 mt-4" style="background-color: #f8f9fa;">
    <div class="container text-center">
        <p class="m-0">© 2024 SBEE. Tous droits réservés. | <a href="#">Politique de confidentialité</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
