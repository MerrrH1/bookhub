<div class="col-md-12">
    <?php
    if ($this->session->userdata('books')) {
        ?>
        <div class="row">
            <h3 class="box-title"><i class="fa fa-th my-2"></i> Daftar Buku yang Dipilih</h3>
            <div class="col-md-12">
                <table class="table table-bordered table-condensed text-center" id="mydata">
                    <thead>
                        <tr>
                            <th class="text-center">#No</th>
                            <th class="text-center">Judul Buku</th>
                        </tr>
                    </thead>
                    <tbody class="g-4 mt-2" id="tbl_loan">
                    </tbody>
                </table>
                <button type="button" class="btn btn-warning float-end">Pinjam Buku</button>
            </div>
        </div>
    <?php } ?>
    <hr>
    <div class="row">
        <div class="box box-danger">
            <div class="box-header with-border color-header">
                <h3 class="box-title"><i class="fa fa-th"></i> Daftar Buku</h3>
                <div class="box-tools pull-right">
                    <a class="btn btn-default btn-sm" href="<?php echo base_url('book'); ?>">
                        <span class="fa fa-refresh"></span> Refresh
                    </a>
                    <?php if ($this->session->userdata('role') == "admin") {
                        echo '<button type="button" class="btn btn-sm btn-success btnTambah" id="btnTambah">' .
                            '<span class="fa fa-plus"></span> Tambah
                    </button>';
                    } ?>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2" id="data-card">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close me-2" data-bs-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> </h4>
            </div>
            <form action="" method="post" id="form_add">
                <div class="modal-body">
                    <input type="hidden" name="book_id" id="book_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Judul Buku</label>
                                <input type="text" name="title" class="form-control input-sm" id="title"
                                    placeholder="Judul Buku...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">- Pilih Kategori -</option>
                                    <?php
                                    var_dump($category);
                                    foreach ($category as $row):
                                        echo "<option value='$row->category_id'>$row->category_name</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Penulis</label>
                                <input type="text" name="author" class="form-control input-sm" id="author"
                                    placeholder="Penulis Buku...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Penerbit</label>
                                <input type="text" name="publisher" class="form-control input-sm" id="publisher"
                                    placeholder="Penerbit Buku...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tahun Terbit</label>
                                <input type="text" name="year" class="form-control input-sm" id="year"
                                    placeholder="Tahun Terbit Buku...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input type="text" name="quantity" class="form-control input-sm" id="quantity"
                                    placeholder="Quantity Buku...">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary " id="btnSimpan"
                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing ">Simpan
                    Data</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var editMode = false;
        showData();
        <?php
        if ($this->session->userdata('books')) {
            echo "showTempBook();";
        }
        ?>

        function showData() {
            var role = $('#role').val();
            $.ajax({
                url: '<?= base_url('book/showData'); ?>',
                type: 'POST',
                dataType: 'JSON',
                success: function (response) {
                    var html = "";

                    for (i = 0; i < response.length; i++) {
                        var item = response[i];
                        html += `
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${item.title}</h5>
                                    <p class="card-text">
                                        Tahun Terbit: ${item.year}<br>
                                        Kategori: ${item.category_name}<br>
                                        Penulis: ${item.author}<br>
                                        Penerbit: ${item.publisher}<br>
                                    </p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <small class="text-body-secondary">Stok: ${item.quantity}</small>
                                    <div class="btn-group" role="group"><form method="post"><input type="hidden" name="book_id" id="book_id" value="${item.book_id}"></form>`;

                        if (role == "member") {
                            if (response[i].quantity > 0) {
                                html += `<button type="button" class="btn btn-sm btn-primary btn_pilih" stok=${item.quantity} data-id="${item.book_id}">Pilih Buku</button>`;
                                // html += `<button type="button" class="btn btn-sm btn-primary btn_pinjam" stok=${item.quantity} data-id="${item.book_id}">Pinjam Buku</button>`;
                            }
                        } else if (role == 'admin') {
                            html += `
                            <button type="button" class="btn btn-sm btn-primary btn_edit" edit-id="${item.book_id}">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger btn_hapus" data-id="${item.book_id}">Hapus</button>
                        `;
                        }

                        html += `</div>
                                </div>
                            </div>
                        </div>`;
                    }

                    $('#data-card').html(html);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }

        function showTempBook() {
            $.ajax({
                url: '<?= base_url('book/showTempBook'); ?>',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    var i;
                    var no = 0;
                    var html = "";
                    for (i = 0; i < response.length; i++) {
                        no++;
                        html += `
                                <tr class="align-middle">
                                  <td>${no}</td>
                                  <td>${response[i].title}</td>
                                </tr>`;
                    }
                    $('#tbl_loan').html(html);
                }
            });
        }

        $(document).on('click', '#btnTambah', function (e) {
            e.preventDefault();
            editMode = false;
            $('#form_add')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#formModal').modal('show');
            $('.modal-title').text('Tambah Buku');
        });

        $(document).on('click', '.btn_edit', function (e) {
            var book_id = $(this).attr('edit-id');
            editMode = true;
            $.ajax({
                url: 'book/showDataById',
                type: 'POST',
                dataType: 'JSON',
                data: { book_id: book_id },
                success: function (response) {
                    $('#form_add')[0].reset();
                    $('.form-group').removeClass('has-error');
                    $('.help-block').empty();
                    $('.modal-title').text('Edit Buku');
                    $('input[name="book_id"]').val(response.book_id);
                    $('input[name="title"]').val(response.title);
                    $('#category option[value=' + response.category_id + ']').attr('selected', 'selected');
                    $('input[name="author"]').val(response.author);
                    $('input[name="publisher"]').val(response.publisher);
                    $('input[name="year"]').val(response.year);
                    $('input[name="quantity"]').val(response.quantity);
                    $('#formModal').modal('show');
                }
            });
        });

        $(document).on("click", "#btnSimpan", function (e) {
            e.preventDefault();
            var $this = $(this);
            var formData = new FormData($('#form_add')[0]);
            if (editMode) {
                var sURL = '<?= base_url('book/modifyData') ?>';
            } else {
                var sURL = '<?= base_url('book/addData') ?>';
            }
            $.ajax({
                url: sURL,
                type: "post",
                dataType: "json",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');
                },
                complete: function () {
                    $this.button('reset');
                },
                success: function (data) {
                    if (data.responce == "success") {
                        $("#form_add")[0].reset();
                        $('.form-group').removeClass('has-error');
                        $('.help-block').empty();
                        $('#formModal').modal('hide');
                        Swal.fire({
                            text: 'Data berhasil di Simpan',
                            icon: 'success',
                            title: 'Saving Succes',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        showData();
                    } else {
                        Swal.fire('Error!', 'Ops! <br>' + data.message, 'error');
                    }
                }
            });
        });

        $(document).on('click', '.btn_hapus', function (e) {
            e.preventDefault();
            var book_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Hapus data?',
                text: 'Anda yakin menghapus data ini?',
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
                            url: '<?php echo base_url(); ?>book/removeData',
                            type: 'POST',
                            dataType: "json",
                            data: { book_id: book_id }
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
                        title: 'Data Telah Berhasil di Hapus',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    showData();
                }
            });
        });

        $(document).on('click', '.btn_pinjam', function (e) {
            e.preventDefault();
            var book_id = $(this).attr('data-id');
            $.ajax({
                url: '<?php echo base_url("book/pinjamBuku"); ?>',
                type: 'post',
                data: { book_id: book_id },
                dataType: 'json',
                success: function (response) {
                    if (response.response == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            text: response.misc
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
                        title: 'Terjadi kesalahan',
                        text: thrownError
                    });
                }
            });
        });

        $(document).on('click', '.btn_pilih', function (e) {
            e.preventDefault();
            var book_id = $(this).attr('data-id');
            console.log(book_id);
            $.ajax({
                url: '<?= base_url('book/addTempBook') ?>',
                type: 'POST',
                data: { book_id: book_id },
                dataType: 'JSON',
                success: function (response) {
                    if (response.response == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            text: response.misc
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
                        title: 'Terjadi kesalahan',
                        text: thrownError
                    });
                }
            })
        })

    });

</script>