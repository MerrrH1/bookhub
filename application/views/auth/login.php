<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <title>Login</title>
</head>

<body>
    <div class="d-flex align-items-center vh-100">
        <div class="container col-md-5 shadow p-3 mb-5 bg-body-tertiary rounded">
            <form action="<?= base_url('auth/login') ?>" method="post">
                <h4 class="text-center mb-4">Login</h4>
                <div class="container mt-3">
                    <?php if ($this->session->flashdata('error')) {
                        echo '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>';
                        $this->session->unset_userdata('error');
                    } ?>

                    <div class="form-group mb-3">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username"
                            class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>"
                            placeholder="Enter Username">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password"
                            class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>"
                            placeholder="Enter Password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>
            </form>
            <div class="text-right mx-3 mt-3">
                <p>Belum Punya Akun?<span><a href="<?= base_url('auth/register'); ?>" class="nav-link text-primary">Daftar</a></span></p>
            </div>
        </div>
    </div>
</body>

</html>