<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-header with-border color-header">
                <h3 class="box-title"><i class="fa fa-th"></i>Data Pinjaman Buku</h3>
                <div class="box-tools pull-right my-4">
                    <a href="<?= base_url('loan'); ?>" class="btn btn-default btn-sm">
                        <span class="fa fa-refresh"></span> Refresh
                    </a>
                    <?php if ($this->session->userdata('role') == "admin") {
                        echo '<button type="button" id="btnTambah" class="btn btn-sm btn-success">
                            <span class="fa fa-plus"></span> Tambah
                            </button>';
                    } ?>

                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-condensed text-center" id="mydata">
                            <thead>
                                <tr>
                                    <th class="text-center">#No</th>
                                    <?php if ($this->session->userdata('role') == "admin") {
                                        echo "<th>Nama</th>";
                                    } ?>
                                    <th class="text-center">Judul Buku</th>
                                    <th class="text-center">Tanggal Pinjam</th>
                                    <th class="text-center">Tanggal Kembali</th>
                                    <th class="text-center">Denda</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class='col-md-2'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_loan">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        showData();

        function showData() {
            var sUrl = $('#role').val() == 'admin' ?
                "<?= base_url('loan/showData') ?>" :
                "<?= base_url('loan/showDataByUser') ?>";
            $.ajax({
                url: sUrl,
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    var role = $('#role').val();
                    var i;
                    var no = 0;
                    var html = "";
                    if (role == "admin") {
                        for (i = 0; i < response.length; i++) {
                            no++;
                            html += `
                                <tr>
                                  <td>${no}</td>
                                  <td>${response[i].first_name.charAt(0).toUpperCase() + (response[i].first_name).slice(1) + " " + (response[i].last_name).charAt(0).toUpperCase() + (response[i].last_name).slice(1)}</td>
                                  <td>${response[i].title}</td>
                                  <td>${response[i].loan_date ? new Date(response[i].loan_date).toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" }) : ""}</td>
                                  <td>${response[i].return_date ? new Date(response[i].return_date).toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" }) : ""}</td>
                                  <td>${response[i].fine_amount ? new Intl.NumberFormat("id-ID", {style: 'currency', currency: 'IDR', minimumFractionDigits: 0}).format(response[i].fine_amount) : ""}</td>
                                  <td>${response[i].status.charAt(0).toUpperCase() + response[i].status.slice(1)}</td>
                                  <td>${response[i].status === "pending" ? `<button class="btn btn-primary btn_konfirmasi" data-id="${response[i].loan_id}">Konfirmasi</button>` : response[i].status === "borrowed" ? `<button class='btn btn-primary btn_kembali' loan-date='${response[i].loan_date}' data-id='${response[i].loan_id}'>Kembalikan Buku</button>` : ""}</td>
                                </tr>`;
                        }
                    } else {
                        for (i = 0; i < response.length; i++) {
                            no++;
                            html += `
                                <tr>
                                  <td>${no}</td>
                                  <td>${response[i].title}</td>
                                  <td>${response[i].loan_date ? new Date(response[i].loan_date).toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" }) : ""}</td>
                                  <td>${response[i].return_date ? new Date(response[i].return_date).toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" }) : ""}</td>
                                  <td>${response[i].fine_amount ? Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', minimumFractionDigits: 0}).format(response[i].fine_amount) : ""}</td>
                                  <td>${response[i].status.charAt(0).toUpperCase() + response[i].status.slice(1)}</td>
                                  <td>${response[i].status === "pending" ? `<button class="btn btn-danger btn_batal" data-id="${response[i].loan_id}">Batal</button>` : response[i].status === "borrowed" ? `<button class='btn btn-primary btn_kembali' data-id='${response[i].loan_id}'>Kembalikan Buku</button>` : ""}</td>
                                </tr>`;

                        }
                    }
                    $('#tbl_loan').html(html);
                }
            });
        }

        $(document).on('click', '.btn_konfirmasi', function (e) {
            e.preventDefault();
            var loan_id = $(this).attr('data-id');
            $.ajax({
                url: '<?= base_url('loan/confirmLoan') ?>',
                data: { loan_id: loan_id },
                dataType: 'JSON',
                type: 'POST',
                success: function (response) {
                    if (response.response == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        });
                        showData();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            });
        })

        $(document).on('click', '.btn_kembali', function () {
            var loan_id = $(this).attr('data-id');
            var loan_date = $(this).attr('loan-date');
            $.ajax({
                url: '<?= base_url('loan/returnBook') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    loan_id: loan_id,
                    loan_date: loan_date
                },
                success: function (response) { 
                    Swal.fire({
                        icon: response.response,
                        title: response.message
                    });
                    showData();
                }
            });
        })

        $(document).on('click', '.btn_batal', function (e) {
            e.preventDefault();
            var loan_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Batalkan peminjaman?',
                text: 'Anda yakin membatalkan peminjaman ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            url: '<?= base_url('loan/cancelLoan'); ?>',
                            type: 'POST',
                            dataType: 'JSON',
                            data: { loan_id: loan_id },
                        })
                            .done(function (data) {
                                resolve(data)
                            })
                            .fail(function () {
                                reject()
                            });
                    });
                },
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Peminjamanan berhasil dibatalkan...',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    showData();
                }
            });
        });
    });
</script>