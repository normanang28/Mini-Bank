<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <div class="basic-form">
                <form id="userForm" class="form-horizontal form-label-left" novalidate  action="<?= base_url('home/aksi_edit_pegawai')?>" method="post">
                    <input type="hidden" name="id" value="<?= $duar->id_user ?>">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">NIP<span style="color: red;">*</span></label>
                            <input type="Number" id="nip" name="nip" 
                            class="form-control text-capitalize" placeholder="NIP" oninput="maxLengthChecknip(this)" value="<?= $duar->nip?>">
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
                            class="form-control text-capitalize" placeholder="Nama Pegawai" value="<?= $duar->nama_pegawai?>">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nomor Telepon<span style="color: red;">*</span></label>
                            <input type="Number" id="no_telp_pegawai" name="no_telp_pegawai" 
                            class="form-control text-capitalize" placeholder="Nomor Telepon" oninput="maxLengthCheck(this)" value="<?= $duar->no_telp_pegawai?>">
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
                              <option value="<?= $duar->jk_pegawai?>"><?= $duar->jk_pegawai; ?></option>
                              <option value="Laki-Laki">Laki-Laki</option>
                              <option value="Perempuan">Perempuan</option>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Tempat Tanggal Lahir<span style="color: red;">*</span></label>
                            <input type="text" id="ttl_pegawai" name="ttl_pegawai" 
                            class="form-control text-capitalize" placeholder="Tempat Tanggal Lahir" value="<?= $duar->ttl_pegawai?>">
                        </div>
                         <div class="mb-3 col-md-6">
                            <label class="form-label">Alamat<span style="color: red;">*</span></label>
                            <input type="text" id="alamat_pegawai" name="alamat_pegawai" 
                            class="form-control text-capitalize" placeholder="Alamat" value="<?= $duar->alamat_pegawai?>">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Username<span style="color: red;">*</span></label>
                            <input type="text" id="username" name="username" 
                            class="form-control text-capitalize" placeholder="Username" maxlength="50" value="<?= $duar->username?>">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Level<span style="color: red;">*</span></label>
                            <div class="col-12">
                                <select id="level" class="form-control col-12" data-validate-length-range="6" data-validate-words="2" name="level" required="required">
                                  <option value="<?= $duar->level?>"><?= $duar->level; ?></option>
                                  <option value="1">Admin</option>
                                  <option value="2">Teller</option>
                                  <option value="3">Customer Service</option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <a href="<?= base_url('/home/pegawai')?>" type="submit" class="btn btn-primary">Cancel</a></button>
                  <button type="submit" id="updateButton" class="btn btn-success" disabled>Update</button>
              </form>
          </div>
      </div>
  </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const userForm = document.getElementById("userForm");
        const updateButton = document.getElementById("updateButton");

        const initialData = {
            nip: "<?= $duar->nip ?>",
            nama_pegawai: "<?= $duar->nama_pegawai ?>",
            alamat_pegawai: "<?= $duar->alamat_pegawai ?>",
            jk_pegawai: "<?= $duar->jk_pegawai ?>",
            no_telp_pegawai: "<?= $duar->no_telp_pegawai ?>",
            ttl_pegawai: "<?= $duar->ttl_pegawai ?>",
            username: "<?= $duar->username ?>",
            level: "<?= $duar->level ?>"
        };

        userForm.addEventListener("change", function() {
            const currentData = {
                nip: document.getElementById("nip").value.trim(),
                nama_pegawai: document.getElementById("nama_pegawai").value.trim(),
                alamat_pegawai: document.getElementById("alamat_pegawai").value.trim(),
                jk_pegawai: document.getElementById("jk_pegawai").value,
                no_telp_pegawai: document.getElementById("no_telp_pegawai").value.trim(),
                ttl_pegawai: document.getElementById("ttl_pegawai").value.trim(),
                username: document.getElementById("username").value.trim(),
                level: document.getElementById("level").value
            };

            const isDataChanged = Object.keys(currentData).some(key => currentData[key] !== initialData[key]);

            if (isDataChanged) {
                updateButton.removeAttribute("disabled");
            } else {
                updateButton.setAttribute("disabled", "disabled");
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