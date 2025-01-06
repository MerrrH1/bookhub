<?= validation_errors(); ?>
<?= $this->session->flashdata('error'); ?>

<form action="<?= base_url('auth/login') ?>" method="post">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>">
        <?= form_error('username', '<div class="invalid-feedback">', '</div>'); ?>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>">
        <?= form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
</form>