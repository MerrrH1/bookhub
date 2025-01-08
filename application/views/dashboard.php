<div class="container mt-4">
        <h1>Selamat datang di BookHUB</h1>
        <p>Ini adalah halaman utama Anda. Di sini Anda dapat melihat ringkasan data peminjaman buku.</p>

        <div class="row">
            <div class="col-md-4">
                <a class="card nav-link" href="<?= base_url('book'); ?>">
                    <div class="card-body">
                        <h5 class="card-title">Total Buku</h5>
                        <p class="card-text"><?= count($book); ?></p>
                    </div>
                </a>
            </div>
        </div>

        </div>