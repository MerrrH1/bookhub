<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-header with-border color-header">
                <h3 class="box-title"><i class="fa fa-th"></i>Daftar Denda</h3>
                <div class="box-tools pull-right my-4">
                    <a href="<?= base_url('fine'); ?>" class="btn btn-default btn-sm">
                        <span class="fa fa-refresh"></span> Refresh
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-condensed text-center" id="mydata">
                            <thead>
                                <tr>
                                    <th class="text-center">#No</th>
                                    <th>Nama</th>
                                    <th class="text-center">Denda</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Tanggal Bayar</th>
                                    <th class='col-md-2'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_fine">
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
            $.ajax({
                url: '<?= base_url('fine/showData') ?>',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    var no = 0;
                    var html = "";
                    for (i = 0; i < response.length; i++) {
                        html += `
                            <tr>
                                <td>${++no}</td>
                                <td>${response[i].first_name.charAt(0).toUpperCase() + (response[i].first_name).slice(1) + " " + (response[i].last_name).charAt(0).toUpperCase() + (response[i].last_name).slice(1)}</td>
                                <td>${new Intl.NumberFormat("id-ID", { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(response[i].fine_amount)}</td>
                                <td>${response[i].fine_status == "0" ? "Belum Bayar" : "Sudah Bayar"}</td>
                                <td>${response[i].fine_status == "1" ? new Date(response[i].paid_date).toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" }) : ""}</td>
                                <td>${response[i].fine_status == "0" ? "<button type='button' class='btn btn-primary btn_bayar' data-id='" + response[i].fine_id + "'>Bayar</button>" : ""}</td>
                            </tr>
                        `;
                    }
                    $('#tbl_fine').html(html);
                }
            });
        }

        $(document).on('click', '.btn_bayar', function(e) {
            e.preventDefault();
            var fine_id = $(this).attr('data-id');
            $.ajax({
                url: '<?= base_url('fine/payFine') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {fine_id: fine_id},
                success: function(response) {
                    Swal.fire({
                        icon: response.response,
                        title: response.message
                    });
                    showData();
                }
            });
        });
    });
</script>