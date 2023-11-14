<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <style>
                @keyframes blink {
                    0% { opacity: 1; }
                    50% { opacity: 0; }
                    100% { opacity: 1; }
                }

                .alert i.blinking-icon {
                    animation: blink 2s infinite;
                }
            </style>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle blinking-icon"></i> Harap berhati-hati saat mengisi formulir. Anda mungkin tidak dapat mengubah data ini jika terjadi kesalahan.
            </div>
            <br>
            <div class="basic-form">
                <form id="userForm" class="form-horizontal form-label-left" novalidate  action="<?= base_url('home/aksi_tambah_penyetoran')?>" method="post">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="control-label col-12">Nama Nasabah<span style="color: red;">*</span></label>
                            <div class="col-12">
                            <select name="id_nasabah" class="form-control text-capitalize" id="id_nasabah" required>
                                <option>~ Pilih Nasabah ~</option>
                                <?php foreach ($n as $nasabah) { ?>
                                    <option class="text-capitalize" value="<?php echo $nasabah->id_nasabah ?>"><?php echo $nasabah->nik ?> - <?php echo $nasabah->nama_nasabah ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Jumlah<span style="color: red;">*</span></label>
                            <input type="number" id="jumlah_penyetoran" name="jumlah_penyetoran" 
                            class="form-control text-capitalize" placeholder="Jumlah">
                        </div>
                  </div>
                  <a href="<?= base_url('/home/penyetoran')?>" type="submit" class="btn btn-primary">Cancel</a></button>
                  <button type="submit" id="submitButton" class="btn btn-success" disabled>Submit</button>
              </form>
          </div>
      </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const menuForm = document.getElementById("menuForm");
    const submitButton = document.getElementById("submitButton");

    const id_nasabah = document.getElementById("id_nasabah");
    const jumlah_penyetoran = document.getElementById("jumlah_penyetoran");

    function checkInputs() {
        if (
            id_nasabah.value !== "~ Pilih Nasabah ~" &&
            jumlah_penyetoran.value.trim() !== ""
        ) {
            submitButton.removeAttribute("disabled");
        } else {
            submitButton.setAttribute("disabled", "disabled");
        }
    }

    id_nasabah.addEventListener("input", checkInputs);
    jumlah_penyetoran.addEventListener("input", checkInputs);

    checkInputs();
});
</script>
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