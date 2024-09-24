<?php
include 'conn.php';

try {
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        echo "
        <tr>
            <td>{$user['id']}</td>
            <td>{$user['first_name']}</td>
            <td>{$user['last_name']}</td>
            <td>{$user['phone_number']}</td>
            <td>{$user['email']}</td>
            <td>
                <button class='btn btn-primary btn-sm' onclick='editUser({$user['id']})'>Düzenle</button>
                <button class='btn btn-danger btn-sm' onclick='deleteUser({$user['id']})'>Sil</button>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
