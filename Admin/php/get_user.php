<?php
include 'conn.php';
// Hata ayıklama için tüm hataları göster (geliştirme sırasında kullanışlıdır, canlı ortamda kapatın)
// error_reporting(E_ALL);

// Çıktının JSON olduğunu ve UTF-8 karakter kodlaması kullanıldığını belirtir
header('Content-Type: application/json; charset=utf-8');

// POST isteğinden 'id' değerini alır
$id = $_POST['id'];

try {
    // SQL sorgusunu hazırlar ve 'id' parametresini bağlar
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id',$id);

    // Sorguyu çalıştırır
    $stmt->execute();

    // Kullanıcı verisini alır (eğer varsa)
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kullanıcı verisini JSON formatına dönüştürür ve çıktı olarak gönderir
    // JSON_UNESCAPED_UNICODE kullanarak Türkçe karakterleri doğru şekilde işler
    if ($user) {
        echo json_encode($user, JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Kullanıcı bulunamadıysa uygun bir hata mesajı gönderir
        echo json_encode(['error' => 'Kullanıcı bulunamadı'], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    // Veritabanı hatası oluşursa hata mesajını JSON formatında gönderir
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}