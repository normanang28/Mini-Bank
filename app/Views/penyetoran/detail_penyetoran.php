<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="right-aligned">
            <a href="<?= base_url('/home/penyetoran/')?>" type="submit" class="btn btn-primary" title="Kembali"><i class="fa fa-reply"></i></a></button>
            <a href="<?= base_url('/home/hapus_penyetoran/'.$gas->id_penyetoran)?>"><button class="btn btn-danger" title="Hapus Penyetoran"><i class="fa fa-trash"></i></button></a>
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
                          <th class="text-center">No Penyetoran</th>
                          <th class="text-center">NIK</th>
                          <th class="text-center">Nama Nasabah</th>
                          <th class="text-center">Tanggal</th>
                          <th class="text-center">Jenis Penyetoran</th>
                          <th class="text-center">Jumlah</th>
                          <th class="text-center">Maker</th>
                      </tr>
                  </thead>
                  <tbody>
                     <?php
                        $no=1;

                        function ubahFormatTanggal($tanggal_asli) {
                            return date("d F Y", strtotime($tanggal_asli));
                        } ?>
                    <tr>
                        <td class="text-center text-capitalize text-dark">MB-<?php echo $gas->id_penyetoran?>(<?php echo $gas->id_nasabah_penyetoran?><?php echo $gas->maker_penyetoran?>)</td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->nik?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->nama_nasabah?></td>
                        <td class="text-center text-capitalize text-dark"><?= ubahFormatTanggal($gas->tanggal_penyetoran) ?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->jenis_penyetoran?></td>
                        <td class="text-center text-capitalize text-dark">Rp. <?= number_format($gas->jumlah_penyetoran, 0, ',', '.') ?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->username?> / <?php echo $gas->tanggal_penyetoran_urut?></td>
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
            window.location.href = '<?= base_url('/home/penyetoran') ?>'; 
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