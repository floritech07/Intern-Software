<?php
include 'db-conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM logiciels WHERE id = :id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $logiciel = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($logiciel) {
            echo json_encode($logiciel);
        } else {
            echo json_encode(['error' => 'Logiciel non trouvé']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID non spécifié']);
}
?>
