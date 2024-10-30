<?php
include 'db-conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;
    $lien = isset($_POST['lien']) ? $_POST['lien'] : null;

    
    if ($id && $nom && $description && $lien) {
        try {
            
            $stmt = $pdo->prepare("UPDATE logiciels SET nom = :nom, description = :description, lien = :lien WHERE id = :id");

            
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':lien', $lien);
            $stmt->bindParam(':id', $id);

            
            $stmt->execute();

            
            echo "<script>alert('Mise à jour réussie !'); window.location.href = 'home.php';</script>";
        } catch (PDOException $e) {
            
            echo "<script>alert('Erreur lors de la mise à jour : " . $e->getMessage() . "'); history.back();</script>";
        }
    } else {
        echo "<script>alert('Tous les champs sont requis.'); history.back();</script>";
    }
} else {
    echo "<script>alert('Méthode de requête non valide.'); history.back();</script>";
}
?>
