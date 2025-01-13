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
    <title>Register</title>
</head>

<body>
    <div class="d-flex align-items-center vh-100">
        <div class="container col-md-5 shadow p-3 mb-5 bg-body-tertiary rounded">
            <form action="<?= base_url('auth/register') ?>" method="post" id="form_register">
                <h4 class="text-center mb-4">Register</h4>
                <div class="container mt-3">
                    <?php if ($this->session->flashdata('error')) {
                        echo '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>';
                        $this->session->unset_userdata('error');
                    } ?>

                    <div class="form-group mb-3">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control <?= form_error('first_name') ? 'is-invalid' : '' ?>"
                            placeholder="Enter First Name"><span class="text-danger">
                            <?php echo form_error('first_name'); ?></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control <?= form_error('last_name') ? 'is-invalid' : '' ?>"
                            placeholder="Enter Last Name">
                        <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username"
                            class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>"
                            placeholder="Enter Username">
                        <span class="text-danger"><?php echo form_error('username'); ?></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email"
                            class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>"
                            placeholder="Enter Email">
                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password"
                            class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>"
                            placeholder="Enter Password">
                        <span class="text-danger"><?php echo form_error('password'); ?></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="form-control <?= form_error('confirm_password') ? 'is-invalid' : '' ?>"
                            placeholder="Confirm Password">
                        <span class="text-danger"><?php echo form_error('confirm_password'); ?></span>
                    </div>

                    <button type="button" class="btn btn-primary w-100" id="daftar">Register</button>
                </div>
            </form>
            <div class="text-right mx-3 mt-3">
                <p>Sudah Punya Akun?<span><a href="<?= base_url('auth/index'); ?>" class="nav-link text-primary">Login</a></span></p>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '#daftar', function () {
            $.ajax({
                url: '<?= base_url('auth/processRegister'); ?>',
                type: 'POST',
                data: $('#form_register').serialize(),
                dataType: 'JSON',
                success: function (response) {
                    if (response.response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            timer: 1000
                        }).then(() => {
                            window.location.href = '<?= base_url('auth/index'); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        icon: 'error',
                        title: xhr.status,
                        text: thrownError
                    });
                }
            });
        });
    });
</script>

</html>