<?php require_once APPPATH . 'helpers/auth_helper.php';
is_logged_in(); ?>

<div class="col-md-12">
    <div class="row">
        <div class="box box-danger">
            <div class="box-header with-border color-header">
                <h3 class="box-title"><i class="fa fa-th"></i> Daftar Kategori Buku</h3>
                <div class="box-tools pull-right">
                    <a class="btn btn-default btn-sm" href="<?php echo base_url('category'); ?>">
                        <span class="fa fa-refresh"></span> Refresh
                    </a>
                    <button type="button" class="btn btn-sm btn-success btnTambah" id="btnTambah">
                        <span class="fa fa-plus"></span> Tambah
                    </button>
                </div>
            </div>
            <div class="box-body my-3">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-condensed text-center" id="mydata">
                            <thead>
                                <tr>
                                    <th style='width:30px;text-align: center;'>#No</th>
                                    <th>Nama Ketegori</th>
                                    <th style='width:200px;text-align: center;'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_data">
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Nama Kategori</label>
                                <input type="text" name="category_name" class="form-control input-sm" id="category_name"
                                    placeholder="Kategori Buku...">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary " id="btnSimpan"
                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing ">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var editMode = false;
        showData();

        function showData() {
            $.ajax({
                url: '<?= base_url('category/showData'); ?>',
                type: 'POST',
                dataType: 'JSON',
                success: function (response) {
                    var i;
                    var no = 0;
                    var html = "";

                    for (i = 0; i < response.length; i++) {
                        html = html +
                            "<tr>" +
                            "<td>" + ++no + "</td>" +
                            "<td>" + response[i].category_name + "</td>" +
                            "<td><center><span>" + "<button edit-id='" + response[i].category_id + "' class='btn btn-primary btn_xs btn_edit'><i class='fa fa-edit'></i> Edit</button>" +
                            "<button class='ms-2 btn btn-danger btn_xs btn_hapus' data-id='" + response[i].category_id + "'<i class='fa fa-trash'></i> Hapus</button>"
                        "</span></center></td>" +
                            "</tr>";
                    }
                    $('#tbl_data').html(html);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
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
            var category_id = $(this).attr('edit-id');
            editMode = true;
            $.ajax({
                url: '<?= base_url("category/showDataById");?>',
                type: 'POST',
                dataType: 'JSON',
                data: { category_id: category_id },
                success: function (response) {
                    $('#form_add')[0].reset();
                    $('.form-group').removeClass('has-error');
                    $('.help-block').empty();
                    $('.modal-title').text('Edit Buku');
                    $('input[name="category_id"]').val(response.category_id);
                    $('input[name="category_name"]').val(response.category_name);
                    $('#formModal').modal('show');
                }
            });
        });

        $(document).on("click", "#btnSimpan", function (e) {
            e.preventDefault();
            var $this = $(this);
            var formData = new FormData($('#form_add')[0]);
            if (editMode) {
                var sURL = '<?= base_url('category/modifyData') ?>';
            } else {
                var sURL = '<?= base_url('category/addData') ?>';
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

        $('#tbl_data').on('click', '.btn_hapus', function (e) {
            e.preventDefault();
            var category_id = $(this).attr('data-id');
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
                            url: '<?php echo base_url(); ?>category/removeData',
                            type: 'POST',
                            dataType: "json",
                            data: { category_id: category_id }
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
    });
</script>