<?php
include 'conn.php';

$email = $_POST['email'];
$password = $_POST['password'];

try {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            session_start();

            $_SESSION['user_id'] = $user['id'];

            echo 'success';
        } else {
            echo 'Şifre yanlış!';
        }
    } else {
        echo 'Kullanıcı adı bulunamadı!';
    }

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

$conn = null;
?>