<?php
include 'conn.php';

$email = $_POST['email'];

$sqlemail = "SELECT id FROM users WHERE email = :email";
$check = $conn->prepare($sqlemail);
$check->bindParam(':email', $email);
$check->execute();

if ($check->rowCount() === 0) {
    echo json_encode(["success" => false, "message" => "Bu e-posta adresi kayıtlı değil."]);
    exit;
}

$user = $check->fetch(PDO::FETCH_ASSOC);
$email_id = $user['id'];

$verification_code = random_int(100000, 999999);

$hashed_code = password_hash($verification_code, PASSWORD_DEFAULT);

try {
    $sqldelete = "DELETE FROM email_verification WHERE email_id = :email_id";
    $stmtdelete = $conn->prepare($sqldelete);
    $stmtdelete->bindParam(':email_id',$email_id);
    $stmtdelete->execute();

    $sql = "INSERT INTO email_verification (email_id, verification_code) VALUES (:email_id, :verification_code)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email_id', $email_id);
    $stmt->bindParam(':verification_code', $hashed_code);
    
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "verification_code" => $verification_code,
        "email" => $email
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Hata: " . $e->getMessage()]);
}

$conn = null;
?>
