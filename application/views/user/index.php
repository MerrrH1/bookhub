<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-header with-border color-header">
                <h3 class="box-title"><i class="fa fa-th"></i>Data User</h3>
                <div class="box-tools pull-right my-4">
                    <a href="<?= base_url('user'); ?>" class="btn btn-default btn-sm">
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
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Peran</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_user">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function(){
        showData();

        function showData() {
            $.ajax({
                url: '<?= base_url('user/showUser'); ?>',
                type: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    var html = "";
                    var no = 0;
                    for(var i = 0; i < response.length; i++) {
                        html += `
                        <tr>
                        <td>${++no}</td>
                        <td>${response[i].first_name.charAt(0).toUpperCase() + (response[i].first_name).slice(1) + " " + (response[i].last_name).charAt(0).toUpperCase() + (response[i].last_name).slice(1)}</td>
                        <td>${response[i].username}</td>
                        <td>${response[i].email}</td>
                        <td>${response[i].role}</td>
                        </tr>
                        `;
                    }
                    $('#tbl_user').html(html);
                }
            });
        }
    });
</script>