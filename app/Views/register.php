<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
     <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register</title>
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
<body>
    <div class="position-absolute" style="top:8px; left:8px; z-index:1050;">
        <a href="<?= base_url('/logout') ?>" class="text-decoration-none text-dark align-items-center back-link" aria-label="Logout">
            <div class="card shadow-sm p-1" style="width:30px; height:30px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-arrow-90deg-left fs-6"></i>
            </div>
        </a>
    </div>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg border-0 rounded-4" style="width: 320px; height: auto;">
            <div class="card-body px-4 py-3">
                <strong class="text-center mb">
                    <h3>Register</h3>
                </strong>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form id="registerForm" action="<?= base_url('/register') ?>" method="post" class="mt-3" novalidate>
                    <div class="mb-3">
                        <label class="form-label small">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="<?= esc(old('email')) ?>" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" value="<?= esc(old('username')) ?>" class="form-control" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input id="regPassword" type="password" name="password" class="form-control" placeholder="Password" required minlength="6">
                            <button class="btn btn-outline-secondary" type="button" id="regTogglePassword"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" id="registerBtn" class="btn btn-outline-dark w-100">
                            <span id="registerBtnSpinner" class="spinner-border spinner-border-sm me-2" role="status" style="display:none;" aria-hidden="true"></span>
                            <span id="registerBtnText">Register</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                // password toggle
                (function(){
                    var t = document.getElementById('regTogglePassword');
                    var p = document.getElementById('regPassword');
                    if (!t || !p) return;
                    t.addEventListener('click', function(){
                        var show = p.type === 'password';
                        p.type = show ? 'text' : 'password';
                        var ic = this.querySelector('i');
                        if (ic) ic.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
                    });
                })();

                // form submit spinner and basic validation
                (function(){
                    var form = document.getElementById('registerForm');
                    var btn = document.getElementById('registerBtn');
                    var spinner = document.getElementById('registerBtnSpinner');
                    if (!form) return;
                    form.addEventListener('submit', function(e){
                        if (!form.checkValidity()){
                            e.preventDefault(); e.stopPropagation(); form.classList.add('was-validated'); return false;
                        }
                        if (spinner) spinner.style.display = '';
                        if (btn) btn.setAttribute('disabled','disabled');
                        return true;
                    }, false);
                })();

                // Flash messages (server-side)
                <?php if (session()->getFlashdata('success')): ?>
                    Swal.fire({ icon: 'success', title: 'Success', text: <?= json_encode(session()->getFlashdata('success')) ?>, confirmButtonText: 'OK' });
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    Swal.fire({ icon: 'error', title: 'Error', text: <?= json_encode(session()->getFlashdata('error')) ?>, confirmButtonText: 'OK' });
                <?php endif; ?>
            });
        </script>
    </body>


</html>