<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="right-aligned">
            <a href="<?= base_url('/home/nasabah/')?>" type="submit" class="btn btn-primary" title="Kembali"><i class="fa fa-reply"></i></a></button>
            <a href="<?= base_url('/home/reset_pw_nasabah/'.$gas->id_nasabah_user)?>"><button class="btn btn-info" title="Reset Password"><i class="fa fa-light fa-key"></i></button></a>
            <a href="<?= base_url('/home/edit_nasabah/'.$gas->id_nasabah_user)?>"><button class="btn btn-warning" title="Edit Pegawai"><i class="fa fa-edit"></i> </button></a>
            <a href="<?= base_url('/home/hapus_nasabah/'.$gas->id_nasabah_user)?>"><button class="btn btn-danger" title="Hapus Pegawai"><i class="fa fa-trash"></i></button></a>
            </div>
            <style>
                .form-container {
                    width: 300px;
                    margin: 0 auto; 
                }

                .right-aligned {
                    text-align: right;
                }
            </style>
            <h1></h1>
            <br>
            <div class="table-responsive">
                <table class="table items-table table table-bordered table-striped verticle-middle table-responsive-sm">
                    <thead>
                        <tr>
                          <th class="text-center">NIK</th>
                          <th class="text-center">Username / Nama Nasabah</th>
                          <th class="text-center">Nomor Telepon</th>
                          <th class="text-center">Jenis Kelamin</th>
                          <th class="text-center">Tempat Tanggal Lahir</th>
                          <th class="text-center">Alamat</th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr>
                        <td class="text-capitalize text-center text-dark"><?php echo $gas->nik?></td>
                        <td class="text-capitalize text-center text-dark"><?php echo $gas->username?> / <?php echo $gas->nama_nasabah?></td>
                        <td class="text-capitalize text-center text-dark"><?php echo $gas->no_telp_nasabah?></td>
                        <td class="text-center text-dark"><?php echo $gas->jk_nasabah?></td>
                        <td class="text-capitalize text-center text-dark"><?php echo $gas->ttl_nasabah?></td>
                        <td class="text-capitalize text-center text-dark"><?php echo $gas->alamat_nasabah?></td>
                  </tr>
              </tbody>
          </table>
      </div>
  </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let timeout;
        const timeoutDuration = 2 * 60 * 1000; 

        function startTimeout() {
            clearTimeout(timeout); 
            timeout = setTimeout(redirectToDashboard, timeoutDuration);
        }

        function redirectToDashboard() {
            window.location.href = '<?= base_url('/home/nasabah') ?>'; 
        }

        document.addEventListener('mousemove', startTimeout);
        document.addEventListener('keypress', startTimeout);

        startTimeout();

        const tableBody = document.querySelector('.table tbody');
        const pageNumbers = document.getElementById('pageNumbers');

        const data = <?= json_encode($duar) ?>; 
        const itemsPerPage = 50;
        let currentPage = 1;
    });
</script>