<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <div class="basic-form">
                <form id="userForm" class="form-horizontal form-label-left" novalidate  action="<?= base_url('home/aksi_tambah_pegawai')?>" method="post">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">NIP<span style="color: red;">*</span></label>
                            <input type="Number" id="nip" name="nip" 
                            class="form-control text-capitalize" placeholder="NIP" oninput="maxLengthChecknip(this)">
                            <script>
                                function maxLengthChecknip(object) {
                                    if (object.value.length > 18)
                                        object.value = object.value.slice(0, 18);
                                }
                            </script>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nama Pegawai<span style="color: red;">*</span></label>
                            <input type="text" id="nama_pegawai" name="nama_pegawai" 
                            class="form-control text-capitalize" placeholder="Nama Pegawai">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nomor Telepon<span style="color: red;">*</span></label>
                            <input type="Number" id="no_telp_pegawai" name="no_telp_pegawai" 
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
                            <select id="jk_pegawai" class="form-control col-12" data-validate-length-range="6" data-validate-words="2" name="jk_pegawai" required="required">
                              <option>~ Pilih Jenis Kelamin~</option>
                              <option value="Laki-Laki">Laki-Laki</option>
                              <option value="Perempuan">Perempuan</option>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tempat Tanggal Lahir<span style="color: red;">*</span></label>
                            <input type="text" id="ttl_pegawai" name="ttl_pegawai" 
                            class="form-control text-capitalize" placeholder="Tempat Tanggal Lahir">
                        </div>
                         <div class="mb-3 col-md-6">
                            <label class="form-label">Alamat<span style="color: red;">*</span></label>
                            <input type="text" id="alamat_pegawai" name="alamat_pegawai" 
                            class="form-control text-capitalize" placeholder="Alamat">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Username<span style="color: red;">*</span></label>
                            <input type="text" id="username" name="username" 
                            class="form-control text-capitalize" placeholder="Username" maxlength="50">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Level<span style="color: red;">*</span></label>
                            <div class="col-12">
                                <select id="level" class="form-control col-12" data-validate-length-range="6" data-validate-words="2" name="level" required="required">
                                  <option>~ Pilih Level Pegawai~</option>
                                  <option value="1">Admin</option>
                                  <option value="2">Teller</option>
                                  <option value="3">Customer Service</option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <a href="<?= base_url('/home/pegawai')?>" type="submit" class="btn btn-primary">Cancel</a></button>
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
            const nip = document.getElementById("nip").value.trim();
            const nama_pegawai = document.getElementById("nama_pegawai").value.trim();
            const alamat_pegawai = document.getElementById("alamat_pegawai").value.trim();
            const jk_pegawai = document.getElementById("jk_pegawai").value;
            const no_telp_pegawai = document.getElementById("no_telp_pegawai").value.trim();
            const ttl_pegawai = document.getElementById("ttl_pegawai").value.trim();
            const username = document.getElementById("username").value.trim();
            const level = document.getElementById("level").value;

            if (nip !== "" && nama_pegawai !== "" && alamat_pegawai !== "" && jk_pegawai !== "~ Pilih Jenis Kelamin ~" && no_telp_pegawai && ttl_pegawai !== "" !== "" && username !== "" && level !== "~ Pilih Level Pegawai ~") {
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
        window.location.href = '<?= base_url('/home/pegawai') ?>'; 
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