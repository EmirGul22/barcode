<?php
include 'conn.php';

$id = $_POST['id'];

try {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    echo "Kullanıcı başarıyla silindi.";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
