<?php
include 'conn.php';
date_default_timezone_set('Europe/Istanbul');


$code = $_POST['code'];
$new_password = $_POST['new_password'];
$email = $_POST['email'];

$sql = "SELECT ev.verification_code, ev.created_at FROM email_verification ev 
        JOIN users u ON ev.email_id = u.id 
        WHERE u.email = :email";
$check = $conn->prepare($sql);
$check->bindParam(':email', $email);
$check->execute();

$old_pass = $check->fetch(PDO::FETCH_ASSOC);

if ($old_pass === false) {
    echo json_encode(["success" => false, "message" => "Geçersiz e-posta adresi."]);
    exit;
}

$hashed_code = $old_pass['verification_code']; 

if (!password_verify($code, $hashed_code)) {
    echo json_encode(["success" => false, "message" => "Geçersiz doğrulama kodu."]);
    exit;
}


$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $sql = "UPDATE users SET password = :password WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);

    $stmt->execute();
    // Kullanıcının email_id'sini alalım
    $sqldelete = "DELETE ev FROM email_verification ev
                JOIN users u ON ev.email_id = u.id 
                WHERE u.email = :email";

    $stmtdelete = $conn->prepare($sqldelete);
    $stmtdelete->bindParam(':email', $email);
    $stmtdelete->execute();


    echo json_encode(["success" => true, "message" => "Şifreniz başarıyla sıfırlandı."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Hata: " . $e->getMessage()]);
}
$conn = null;
?>
