<?php

session_start();
include 'db-conn.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

$userId = $_SESSION['user_id'];


$query = "SELECT * FROM logiciels";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $logiciels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur dans la requête SQL : " . $e->getMessage());
}


$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchQuery) {
    $query = "SELECT * FROM logiciels WHERE nom LIKE :search OR description LIKE :search";
} else {
    $query = "SELECT * FROM logiciels";
}

try {
    $stmt = $pdo->prepare($query);

    if ($searchQuery) {
        $stmt->execute(['search' => "%$searchQuery%"]);
    } else {
        $stmt->execute();
    }

    $logiciels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur dans la requête SQL : " . $e->getMessage());
}


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
   <style>
    .card-img-top{
        
    }
   </style>
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

    <main>
        <div class="d-flex justify-content-between align-items-center py-3 sort-container">
            <div class="ms-5">
                <label for="sort" class="me-2">Trier par :</label>
                <select id="sort" class="form-select">
                    <option>Date d'ajout</option>
                    <option>Nom</option>
                    <option>Catégorie</option>
                </select>
            </div>
            <div class="me-5">
                <button class="btn btn-primary" id="addSoftwareBtn" onclick="redirectToAddSoftware()">Ajouter un logiciel</button>
            </div>
        </div>

        <div class="container py-5">
            <div class="row row-cols-1 row-cols-md-3 g-4 py-5">
            <?php foreach ($logiciels as $logiciel) : ?>
    <div class="col">
        <div class="card">
            <img src="<?= $logiciel['image']; ?>" class="card-img-top" alt="<?= $logiciel['nom']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?= $logiciel['nom']; ?></h5>
                <p class="card-text"><?= $logiciel['description']; ?></p>
            </div>
            <div class="mb-5 d-flex justify-content-around">
                <a href="<?= $logiciel['lien']; ?>" class="btn btn-primary" target="_blank">Voir Plus</a>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="update_software.php?id=<?= $logiciel['id']; ?>">Modifier</a></li>
                        <li><a class="dropdown-item" href="#" onclick="informationsLogiciel('<?= $logiciel['id']; ?>')">Informations</a></li>
                        <li><a class="dropdown-item" href="#" onclick="supprimerLogiciel('<?= $logiciel['id']; ?>')">Supprimer</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

            </div>
        </div>


       
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Détails du Logiciel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

    </main>

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
        function showInfoModal(logicielId) {
        fetch(`get_software_info.php?id=${logicielId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalContent').innerHTML = `
                    <p><strong>Nom :</strong> ${data.nom}</p>
                    <p><strong>Description :</strong> ${data.description}</p>
                    <p><strong>Ajouté par :</strong> ${data.auteur}</p>
                    <p><strong>Date d'ajout :</strong> ${data.date_ajout}</p>
                `;
                const modal = new bootstrap.Modal(document.getElementById('infoModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    function deleteSoftware(logicielId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce logiciel ?')) {
            fetch(`delete_software.php?id=${logicielId}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (response.ok) {
                    alert('Logiciel supprimé avec succès.');
                    location.reload(); 
                } else {
                    alert('Erreur lors de la suppression du logiciel.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    }

    function informationsLogiciel(logicielId) {
    fetch(`get_software_info.php?id=${logicielId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalContent').innerHTML = `
                <p><strong>Nom :</strong> ${data.nom}</p>
                <p><strong>Description :</strong> ${data.description}</p>
                <p><strong>Ajouté par :</strong> ${data.auteur}</p>
                <p><strong>Date d'ajout :</strong> ${data.date_ajout}</p>
            `;
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function supprimerLogiciel(logicielId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce logiciel ?')) {
        fetch(`delete_software.php?id=${logicielId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Logiciel supprimé avec succès.');
                    location.reload(); 
                } else {
                    alert('Erreur lors de la suppression : ' + (data.message || 'Erreur inconnue.'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression du logiciel.');
            });
    }
}

function searchSoftware(event) {
    event.preventDefault(); 

    const query = document.getElementById('searchInput').value;

    
    window.location.href = `home.php?search=${encodeURIComponent(query)}`;
}

    </script>
</body>
</html>
