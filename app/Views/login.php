<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            font-family: monospace;
        }

        .form-control:focus {
            box-shadow: none;
            outline: none;
            border-color: #aaa;
            /* optional: custom border color */
        }
    </style>
</head>

<body class="bg-light">

    <body class="bg-light">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow-lg border-0 rounded-4" style="width: 320px; height: auto;">
                <div class="card-body px-4 py-5">
                    <h4 class="card-title mb-4 fw-bold text-center"><i class="bi bi-person-check"></i>Login</h4>
                    <form id="loginForm" action="<?= base_url('/login') ?>" method="post" novalidate>
                        <!-- Username Field -->
                        <div class="mb-3">
                            <label for="username" class="form-label text-start w-100">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-start w-100">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Show password"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" id="loginBtn" class="btn btn-outline-dark w-100">
                                <span id="loginBtnSpinner" class="spinner-border spinner-border-sm me-2" role="status" style="display:none;" aria-hidden="true"></span>
                                <span id="loginBtnText"><i class="bi bi-key"></i> Login</span>
                            </button>
                        </div>

                    </form>
                    <!-- Optional Links -->
                    <div class="mt-4 text-center">
                        <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none small link-dark">Forgot password?</a><br>
                        <a href="#" class="text-decoration-none small link-dark">No Account? <a href="<?php echo base_url('/register'); ?>" class="link-dark text-decoration-underline small">Register</a> </a>
                    </div>
                </div>
            </div>
        </div>
    </body>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (session()->getFlashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Registered Successfully',
                    text: <?= json_encode(session()->getFlashdata('success')) ?>,
                    width:620, 
                    padding: '1em',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Error',
                    text: <?= json_encode(session()->getFlashdata('error')) ?>,
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>

            // Toggle password visibility (eye icon)
            (function(){
                var toggleBtn = document.getElementById('togglePassword');
                var pwdInput = document.getElementById('password');
                if (!toggleBtn || !pwdInput) return;

                toggleBtn.addEventListener('click', function () {
                    var isHidden = pwdInput.type === 'password';
                    pwdInput.type = isHidden ? 'text' : 'password';
                    var icon = this.querySelector('i');
                    if (icon) {
                        icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
                    }
                    this.setAttribute('aria-pressed', String(isHidden));
                });
            })();
        });
    </script>
</body>

</html>