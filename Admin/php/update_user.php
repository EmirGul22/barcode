<?php
include 'conn.php';

$id = $_POST['id'];
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$phone = $_POST['phone']; 
$email = $_POST['email'];

//telefon numarası kontrolü
$phone_regex = '/^0[0-9]{10}$/';
if (!preg_match($phone_regex, $phone)) {
    echo "Geçersiz telefon numarası. Telefon numarası 0 ile başlamalı ve 11 haneli olmalıdır.";
    exit;
}

try {

     // Geliştirilmiş e-posta kontrolü
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Geçersiz e-posta adresi.";
        exit;
    }
    
    // E-posta kontrolü
    $sqlemail = "SELECT email FROM users WHERE email = :email AND id <> :id" ;
    $check = $conn->prepare($sqlemail);
    $check->bindParam(':email', $email);
    $check->bindParam(':id', $id);
    $check->execute();

    // E-posta kontrolü
    if ($check->rowCount() > 0) {
        echo "Bu e-posta adresi zaten kayıtlı.";
        exit; // E-posta zaten varsa işlemi durdur
    }

    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone_number = :phone, email = :email WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    echo "Kullanıcı başarıyla güncellendi.";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
