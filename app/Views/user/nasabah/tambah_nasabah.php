<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <div class="basic-form">
                <form id="userForm" class="form-horizontal form-label-left" novalidate  action="<?= base_url('home/aksi_tambah_nasabah')?>" method="post">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">NIK<span style="color: red;">*</span></label>
                            <input type="Number" id="nik" name="nik" 
                            class="form-control text-capitalize" placeholder="NIK" oninput="maxLengthChecknip(this)">
                            <script>
                                function maxLengthChecknip(object) {
                                    if (object.value.length > 16)
                                        object.value = object.value.slice(0, 16);
                                }
                            </script>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nama Nasabah<span style="color: red;">*</span></label>
                            <input type="text" id="nama_nasabah" name="nama_nasabah" 
                            class="form-control text-capitalize" placeholder="Nama Nasabah">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nomor Telepon<span style="color: red;">*</span></label>
                            <input type="Number" id="no_telp_nasabah" name="no_telp_nasabah" 
                            class="form-control text-capitalize" placeholder="Nomor Telepon" oninput="maxLengthCheck(this)">
                            <script>
                                function maxLengthCheck(object) {
                                    if (object.value.length > 14)
                                        object.value = object.value.slice(0, 14);
                                }
                            </script>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Jenis Kelamin<span style="color: red;">*</span></label>
                            <div class="col-12">
                            <select id="jk_nasabah" class="form-control col-12" data-validate-length-range="6" data-validate-words="2" name="jk_nasabah" required="required">
                              <option>~ Pilih Jenis Kelamin~</option>
                              <option value="Laki-Laki">Laki-Laki</option>
                              <option value="Perempuan">Perempuan</option>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tempat Tanggal Lahir<span style="color: red;">*</span></label>
                            <input type="text" id="ttl_nasabah" name="ttl_nasabah" 
                            class="form-control text-capitalize" placeholder="Tempat Tanggal Lahir">
                        </div>
                         <div class="mb-3 col-md-6">
                            <label class="form-label">Alamat<span style="color: red;">*</span></label>
                            <input type="text" id="alamat_nasabah" name="alamat_nasabah" 
                            class="form-control text-capitalize" placeholder="Alamat">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Username<span style="color: red;">*</span></label>
                            <input type="text" id="username" name="username" 
                            class="form-control text-capitalize" placeholder="Username" maxlength="50">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Password<span style="color: red;">*</span></label>
                            <input type="text" id="password" name="password" 
                            class="form-control" placeholder="Password" maxlength="50">
                        </div>
                  </div>
                  <a href="<?= base_url('/home/nasabah')?>" type="submit" class="btn btn-primary">Cancel</a></button>
                  <button type="submit" id="submitButton" class="btn btn-success" disabled>Submit</button>
              </form>
          </div>
      </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const userForm = document.getElementById("userForm");
        const submitButton = document.getElementById("submitButton");

        userForm.addEventListener("change", function() {
            const nik = document.getElementById("nik").value.trim();
            const nama_nasabah = document.getElementById("nama_nasabah").value.trim();
            const alamat_nasabah = document.getElementById("alamat_nasabah").value.trim();
            const jk_nasabah = document.getElementById("jk_nasabah").value;
            const no_telp_nasabah = document.getElementById("no_telp_nasabah").value.trim();
            const ttl_nasabah = document.getElementById("ttl_nasabah").value.trim();
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();

            if (nik !== "" && nama_nasabah !== "" && alamat_nasabah !== "" && jk_nasabah !== "~ Pilih Jenis Kelamin ~" && no_telp_nasabah && ttl_nasabah !== "" !== "" && username !== "" && password !== "") {
                submitButton.removeAttribute("disabled");
            } else {
                submitButton.setAttribute("disabled", "disabled");
            }
        });
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