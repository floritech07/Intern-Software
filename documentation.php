<?php
session_start();
include 'db-conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


$query = "SELECT * FROM logiciels";
$stmt = $pdo->prepare($query);
$stmt->execute();
$logiciels = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
    <h1>Documentation des Logiciels</h1>
    <table class="table table-responsive table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom du Logiciel</th>
            <th>Documentation</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($logiciels as $index => $logiciel): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($logiciel['nom']) ?></td>
                <td>
                    <?php if (!empty($logiciel['documentation'])): ?>
                        <a href="./uploads/<?= htmlspecialchars($logiciel['documentation']) ?>" target="_blank">Télécharger</a>
                    <?php else: ?>
                        Pas de documentation disponible
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (empty($logiciel['documentation'])): ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal" data-logiciel-id="<?= $logiciel['id'] ?>">Ajouter</button>
                    <?php else: ?>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadModal" data-logiciel-id="<?= $logiciel['id'] ?>">Modifier</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Ajouter / Modifier Documentation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="logiciel_id" id="logiciel_id">
                    <div class="mb-3">
                        <label for="document" class="form-label">Sélectionnez un fichier</label>
                        <input type="file" class="form-control" name="document" id="document" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
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
<script>
    
    var uploadModal = document.getElementById('uploadModal');
    uploadModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; 
        var logicielId = button.getAttribute('data-logiciel-id'); 
        var modalTitle = uploadModal.querySelector('.modal-title');
        var logicielInput = document.getElementById('logiciel_id');

        logicielInput.value = logicielId;
    });

    
    document.getElementById('uploadForm').onsubmit = function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        
        
        fetch('upload.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  
                  location.reload();
              } else {
                  alert('Erreur: ' + data.message);
              }
          }).catch(error => console.error('Erreur:', error));
    };
</script>
    
</body>
</html>
