<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();

    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Kullanıcı Yönetimi</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-end">
                    </div>

                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <div class="mt-3 text-center">
                        <a href="?logout=1" class="btn btn-danger btn-sm">Çıkış Yap</a>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>

            <!-- "Yeni Kullanıcı Ekle" Butonu -->
            <div class="mb-3 text-end">
                <button class="btn btn-success" onclick="showAddUserForm()">Yeni Kullanıcı Ekle</button>
            </div>



            <!-- Kullanıcı Listesi Tablosu -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Email</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <!-- Kullanıcılar buraya AJAX ile yüklenecek -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Yeni Kullanıcı Ekleme Formu Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Yeni Kullanıcı Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="first-name" class="form-label">Ad</label>
                        <input type="text" class="form-control" id="first-name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last-name" class="form-label">Soyad</label>
                        <input type="text" class="form-control" id="last-name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefon</label>
                        <input type="text" class="form-control" id="phone" name="phone" pattern="\d{11}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="registerUser()">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Kullanıcı Düzenleme Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Kullanıcı Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="edit-user-id" name="id">
                    <div class="mb-3">
                        <label for="edit-first-name" class="form-label">Ad</label>
                        <input type="text" class="form-control" id="edit-first-name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-last-name" class="form-label">Soyad</label>
                        <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-phone" class="form-label">Telefon</label>
                        <input type="text" class="form-control" id="edit-phone" name="phone" pattern="\d{11}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Kullanıcıları Yükleme Fonksiyonu (AJAX ile)
    function loadUsers() {
        $.ajax({
            url: 'php/get_users.php', // Kullanıcıları getiren PHP dosyası
            type: 'GET',
            success: function(response) {
                $('#userTableBody').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Kullanıcılar yüklenirken hata oluştu: ", error);
            }
        });
    }

    // Yeni Kullanıcı Ekleme Formunu Gösterme
    function showAddUserForm() {
        $('#addUserModal').modal('show');
    }

    function registerUser() {
        const firstName = $('#first-name').val();
        const lastName = $('#last-name').val();
        const phone = $('#phone').val();
        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: '../php/register.php',
            type: 'POST',
            data: {
                first_name: firstName,
                last_name: lastName,
                phone: phone,
                email: email,
                password: password
            },
            success: function(response) {
                alert(response);
                if (response.trim() === 'Kayıt başarılı!') {
                    $('#addUserModal').modal('hide');
                    loadUsers();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    // Kullanıcıyı Silme Fonksiyonu
    function deleteUser(userId) {
        if (confirm("Bu kullanıcıyı silmek istediğinize emin misiniz?")) {
            $.ajax({
                url: 'php/delete_user.php', // Kullanıcıyı silen PHP dosyası
                type: 'POST',
                data: { id: userId },
                success: function(response) {
                    alert(response);
                    loadUsers(); // Kullanıcıları tekrar yükle
                },
                error: function(xhr, status, error) {
                    console.error("Kullanıcı silinirken hata oluştu: ", error);
                }
            });
        }
    }

    function editUser(userId) {
    $.ajax({
        url: 'php/get_user.php',
        type: 'POST',
        data: { id: userId },
        success: function(response) {
            // JSON.parse() çağrısını kaldırın, response zaten bir JavaScript nesnesidir
            const user = response; 
            $('#edit-user-id').val(user.id);
            $('#edit-first-name').val(user.first_name);
            $('#edit-last-name').val(user.last_name);
            $('#edit-phone').val(user.phone_number); 
            $('#edit-email').val(user.email);
            $('#editUserModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error("Kullanıcı verisi alınırken hata oluştu: ", error);
        }
    });
}

    function updateUser() {
        const userId = $('#edit-user-id').val();
        const firstName = $('#edit-first-name').val();
        const lastName = $('#edit-last-name').val();
        const phone = $('#edit-phone').val();
        const email = $('#edit-email').val();

        $.ajax({
            url: 'php/update_user.php', 
            type: 'POST',
            data: {
                id: userId,
                first_name: firstName,
                last_name: lastName,
                phone: phone,   
                email: email
            },
            success: function(response) {
                alert(response);
                if (response.trim() === 'Kullanıcı başarıyla güncellendi.') {
                    $('#addUserModal').modal('hide');
                    loadUsers();
                }
            },
            error: function(xhr, status, error) {
                console.error("Kullanıcı güncellenirken hata oluştu: ", error);
            }
        });
    }

    $(document).ready(function() {
        loadUsers();
    });
</script>

</body>
</html>
