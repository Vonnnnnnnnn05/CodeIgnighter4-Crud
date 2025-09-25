<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3">Reset Password</h4>

          <?php if (session()->getFlashdata('success')): ?>
            <script>Swal.fire({icon:'success',title:<?= json_encode(session()->getFlashdata('success')) ?>,timer:2000,showConfirmButton:false});</script>
          <?php endif; ?>
          <?php if (session()->getFlashdata('error')): ?>
            <script>Swal.fire({icon:'error',title:<?= json_encode(session()->getFlashdata('error')) ?>});</script>
          <?php endif; ?>

          <form action="<?= base_url('reset-password') ?>" method="post">
            <input type="hidden" name="token" value="<?= esc($token) ?>">
            <div class="mb-3">
              <label for="password" class="form-label">New password</label>
              <input type="password" class="form-control" id="password" name="password" required minlength="6">
            </div>
            <div class="mb-3">
              <label for="password_confirm" class="form-label">Confirm password</label>
              <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Set Password</button>
            </div>
          </form>

          <div class="mt-3 text-center">
            <a href="<?= base_url('/') ?>">Back to login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA6V2b6a6Q9p5Q5p5Q5p5" crossorigin="anonymous"></script>
</body>
</html>