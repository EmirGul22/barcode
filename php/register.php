<?php
include 'conn.php';

// POST verilerini al
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];


// Şifre gereksinimleri
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo "Şifre en az 8 karakter uzunluğunda olmalı ve en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.";
    exit;
}

//telefon numarası kontrolü
$phone_regex = '/^0[0-9]{10}$/';
if (!preg_match($phone_regex, $phone)) {
    echo "Geçersiz telefon numarası. Telefon numarası 0 ile başlamalı ve 11 haneli olmalıdır.";
    exit;
}

// Şifreyi hashle
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Geliştirilmiş e-posta kontrolü
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Geçersiz e-posta adresi.";
        exit;
    }
    
    // E-posta kontrolü
    $sqlemail = "SELECT email FROM users WHERE email = :email";
    $check = $conn->prepare($sqlemail);
    $check->bindParam(':email', $email);
    $check->execute();

    // E-posta kontrolü
    if ($check->rowCount() > 0) {
        echo "Bu e-posta adresi zaten kayıtlı.";
        exit; // E-posta zaten varsa işlemi durdur
    }

    // Kayıt ekleme sorgusu
    $sql = "INSERT INTO users (first_name, last_name, phone_number, email, password) VALUES (:first_name, :last_name, :phone_number, :email, :password)";
    $stmt = $conn->prepare($sql);

    // Parametreleri bağla
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone_number', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Kayıt ekle
    $stmt->execute();

    echo "Kayıt başarılı!";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

// Bağlantıyı kapat
$conn = null;
?>
