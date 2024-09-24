<?php
include 'php/conn.php'; // Veritabanı bağlantısını dahil et

try {
    // Sorgu oluştur
    $sql = "SELECT ev.id, u.email, ev.verification_code, ev.created_at
            FROM email_verification ev
            JOIN users u ON ev.email_id = u.id";
    
    // Sorguyu hazırla ve çalıştır
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Sonuçları al
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tablo başlıkları
    echo "<h2>Email Doğrulama Kayıtları</h2>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>ID</th>
                <th>E-posta</th>
                <th>Doğrulama Kodu</th>
                <th>Oluşturma Tarihi</th>
            </tr>";

    // Her bir kayıt için satır oluştur
    foreach ($results as $row) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['email']}</td>
                <td>{$row['verification_code']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

// Bağlantıyı kapat
$conn = null;
?>
