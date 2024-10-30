<?php
session_start();
include 'db-conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $logicielId = $_POST['logiciel_id'];
    $uploadDir = 'uploads/';
    $fileName = $_FILES['document']['name'];
    $filePath = $uploadDir . basename($fileName);

    
    if (move_uploaded_file($_FILES['document']['tmp_name'], $filePath)) {
        
        $query = "UPDATE logiciels SET documentation = :documentation WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['documentation' => $fileName, 'id' => $logicielId]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'upload du fichier.']);
    }
}
