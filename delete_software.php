<?php
include 'db-conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $query = "DELETE FROM logiciels WHERE id = :id";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucun logiciel trouvé avec cet ID.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID non spécifié.']);
}
?>
