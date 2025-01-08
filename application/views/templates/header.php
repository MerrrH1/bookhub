<?php if (!$this->session->userdata('user_id')) {
    header('Location: ' . base_url('auth/login'));
    exit;
} ?>
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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <title><?= $title; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url(); ?>">BookHUB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a href="<?= base_url() ?>" class="nav-link">Dashboard</a>
                    <a class="nav-link" href="<?= base_url('book'); ?>">Buku</a>
                    <a class="nav-link" href=<?= base_url('category'); ?>>Kategori</a>
                    <a class="nav-link" href="<?= base_url('loan'); ?>">Peminjaman</a>
                    <?php if ($this->session->userdata("role") == "admin") {
                        echo '<a class="nav-link" href="#">Member</a>
                        <a class="nav-link" href="#">Ulasan</a>';
                    } ?>
                </div>
            </div>
            <div class="d-flex">
                <input type="hidden" name="user_id" id="user_id" value="<?= $this->session->userdata('user_id'); ?>">
                <input type="hidden" name="role" id="role" value="<?= $this->session->userdata('role'); ?>">
                <a href="<?= base_url('auth/logout'); ?>" class="btn btn-success">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container my-3">