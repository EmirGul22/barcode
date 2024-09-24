<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarCode </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        
        <div class="col-md-4">


            <!-- Hata Mesajı Gösterimi -->
            <div id="error-message" class="alert alert-danger d-none" role="alert">
                <!-- hata mesajı görüntülemesi burada yapılacak -->
            </div>

            <!-- Giriş Yap Formu -->
            <div id="login-form">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Giriş Yap</h4>
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-Mail Adresiniz</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" onclick="loginUser()">Giriş Yap</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <button class="btn btn-link" onclick="showForgotPasswordForm()">Şifremi Unuttum</button>
                        </div>
                        <div class="mt-3 text-center">
                            <button class="btn btn-link" onclick="showRegisterForm()">Kayıt Ol</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Şifreyi Unuttum Formu -->
            <div id="forgot-password-form" class="d-none">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Şifremi Unuttum</h4>
                        <form id="forgotPasswordForm">
                            <div class="mb-3">
                                <label for="forgot-email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="forgot-email" name="forgot_email" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-warning" onclick="sendVerificationCode()">Kod Gönder</button>
                            </div>
                        </form>

                        <form id="reset-code-form" onsubmit="verifyResetCode(event)">
                            <input type="hidden" name="emailhidden" id="emailhidden" value="emirgul231@gmail.com">
                            <label for="reset-code" class="form-label">Doğrulama Kodu</label>
                            <input type="text" id="reset-code" placeholder="Doğrulama kodunu girin" class="form-control" required>
                            <label for="new-password" class="form-label">Yeni Şifreyi Giriniz</label>
                            <input type="password" id="new-password" placeholder="Yeni şifreyi girin" class="form-control" required>
                            <button type="submit" class="btn btn-success">Şifreyi Sıfırla</button>
                        </form>

                    </div>
                </div>
            </div>
            <script>
                function showForgotPasswordForm() {
                    document.getElementById('login-form').classList.add('d-none');
                    document.getElementById('register-form').classList.add('d-none');
                    document.getElementById('forgot-password-form').classList.remove('d-none');
                }

                function sendVerificationCode() {
                    const email = $('#forgot-email').val();

                    $.ajax({
                        url: 'php/send_verification_code.php',
                        type: 'POST',
                        data: {
                            email: email
                        },
                        success: function(response) {
                            const data = JSON.parse(response);

                            if (data.success) {
                                $('#emailhidden').val(data.email);
                                alert('Doğrulama Kodunuz Gönderildi');
                                const formData = new URLSearchParams();
                                formData.append('email', 'emirgul231@gmail.com');
                                formData.append('password', 'fshz wmvx guwu okcf');
                                formData.append('recipients', data.email);
                                formData.append('subject', 'Doğrulama Kodu');
                                formData.append('body', 'Doğrulama Kodunuz : '+data.verification_code+'');
                                formData.append('apiKey', 'nMnBde7Hf5');
                                
                                fetch('https://defcode.com.tr/phpmailer/index.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: formData
                                }).then(response => response.text())
                                .then(data => console.log(data))
                                .catch(error => console.error('Hata:', error));
                            } else {
                                alert(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        }
                    });
                }

                function verifyResetCode(event) {
                    event.preventDefault();

                    const code = $('#reset-code').val();
                    const newPassword = $('#new-password').val();
                    const email = $('#emailhidden').val();

                    $.ajax({
                        url: 'php/verify_reset_code.php', // Doğrulama kodu kontrolü için PHP dosyası
                        type: 'POST',
                        data: {
                            code: code,
                            new_password: newPassword,
                            email: email
                        },
                        success: function(response) {
                            const data = JSON.parse(response);

                            if (data.success) {
                                alert('Şifreniz başarıyla sıfırlandı.');
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                        }
                    });
                }


            </script>

            <!-- Kayıt Ol Formu -->
            <div id="register-form" class="d-none">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Kayıt Ol</h4>
                        <form id="registerForm">
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
                                <label for="register-email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="register-email" name="register_email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password-register" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="password-register" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" onclick="registerUser()">Kayıt Ol</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <button class="btn btn-link" onclick="showLoginForm()">Giriş Yap</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ajax ile aynı sayfada giriş yapma ve hata çözme -->

<script>
    function loginUser() {
        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                console.log("Server Response: ", response);
                if (response.trim() === 'success') { 
                    window.location.href = 'Admin/index.php';
                } else {
                    $('#error-message').removeClass('d-none').text(response);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>

<!-- ajax ile aynı sayfada giriş yapma ve hata çözme -->


<!-- ajax ile aynı sayfada kayıt etme -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function registerUser() {
        const firstName = $('#first-name').val();
        const lastName = $('#last-name').val();
        const phone = $('#phone').val();
        const email = $('#register-email').val(); 
        const password = $('#password-register').val();

        $.ajax({
            url: 'php/register.php',
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
                    showLoginForm(); 
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>
<!-- ajax ile aynı sayfada kayıt etme -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Giriş yap formunu göster
    function showLoginForm() {
        document.getElementById('login-form').classList.remove('d-none');
        document.getElementById('register-form').classList.add('d-none');
    }

    // Kayıt ol formunu göster
    function showRegisterForm() {
        document.getElementById('login-form').classList.add('d-none');
        document.getElementById('register-form').classList.remove('d-none');
    }

    // Backend'den şifre hatalıysa hata mesajını göster
    const hasError = false; // Bu değeri backend'den gelecek şekilde dinamik yapın
    if (hasError) {
        document.getElementById('error-message').classList.remove('d-none');
    }
</script>

</body>
</html>
