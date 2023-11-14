<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <div class="basic-form">
                <form id="profileForm" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate action="<?= base_url('home/aksi_ganti_profile_pegawai')?>" method="post">

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="control-label col-12">Replace New Profile<span style="color: red;">*</span></label>
                            <div class="col-12">
                                <input type="file" name="foto" class="form-file-input form-control col-12">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">NIP<span style="color: red;">*</span></label>
                            <input type="number" id="nip" name="nip" 
                            class="form-control text-capitalize" placeholder="NIP" value="<?= $users->nip?>">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nama Pegawai<span style="color: red;">*</span></label>
                            <input type="text" id="nama_pegawai" name="nama_pegawai" 
                            class="form-control text-capitalize" placeholder="Nama Pegawai" value="<?= $users->nama_pegawai?>">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nomor Telepon<span style="color: red;">*</span></label>
                            <input type="Number" id="no_telp_pegawai" name="no_telp_pegawai" 
                            class="form-control text-capitalize" placeholder="Nomor Telepon" oninput="maxLengthCheck(this)" value="<?= $users->no_telp_pegawai?>">
                            <script>
                                function maxLengthCheck(object) {
                                    if (object.value.length > 14)
                                        object.value = object.value.slice(0, 14);
                                }
                            </script>
                        </div>
                        <div class="mb-3 col-md-6">
                          <label class="control-label col-12" >Jenis Kelamin<span style="color: red;">*</span>
                          </label>
                          <div class="col-12">
                            <select id="jk_pegawai" class="form-control col-12" data-validate-length-range="6" data-validate-words="2" name="jk_pegawai" required="required">
                              <option  value="<?= $users->jk_pegawai?>"><?= $users->jk_pegawai; ?></option>
                              <option value="Laki-Laki">Laki-Laki</option>
                              <option value="Perempuan">Perempuan</option>
                          </select>
                      </div>
                  </div>
                  <div class="mb-3 col-md-6">
                      <label class="form-label">Tempat Tanggal Lahir<span style="color: red;">*</span></label>
                      <input type="text" id="ttl_pegawai" name="ttl_pegawai" 
                    class="form-control text-capitalize" placeholder="Tempat Tanggal Lahir" value="<?= $users->ttl_pegawai?>">
                  </div>
                  <div class="mb-3 col-md-6">
                      <label class="form-label">Alamat<span style="color: red;">*</span></label>
                      <input type="text" id="alamat_pegawai" name="alamat_pegawai" 
                    class="form-control text-capitalize" placeholder="Alamat" value="<?= $users->alamat_pegawai?>">
                  </div>
                  <div class="mb-3 col-md-6">
                    <label class="form-label" >Username<span style="color: red;">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Username" required="required" class="form-control text-capitalize" value="<?= $use->username?>">
                  </div>
                </div>
        <!-- <a onclick="history.back()" type="submit" class="btn btn-primary">Cancel</a></button> -->
        <button type="submit" id="updateButton" class="btn btn-success" disabled>Update</button>
    </form>
</div>
</div>
</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const profileForm = document.getElementById("profileForm");
        const updateButton = document.getElementById("updateButton");

        const initialData = {
            nip: "<?= $users->nip ?>",
            nama_pegawai: "<?= $users->nama_pegawai ?>",
            no_telp_pegawai: "<?= $users->no_telp_pegawai ?>",
            jk_pegawai: "<?= $users->jk_pegawai ?>",
            ttl_pegawai: "<?= $users->ttl_pegawai ?>",
            alamat_pegawai: "<?= $users->alamat_pegawai ?>",
            username: "<?= $use->username ?>"
        };

        profileForm.addEventListener("change", function() {
            const currentData = {
                nip: document.getElementById("nip").value.trim(),
                nama_pegawai: document.getElementById("nama_pegawai").value.trim(),
                no_telp_pegawai: document.getElementById("no_telp_pegawai").value.trim(),
                jk_pegawai: document.getElementById("jk_pegawai").value,
                ttl_pegawai: document.getElementById("ttl_pegawai").value.trim(),
                alamat_pegawai: document.getElementById("alamat_pegawai").value.trim(),
                username: document.getElementById("username").value.trim(),
                foto: document.querySelector('input[type="file"]').value
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
        window.location.href = '<?= base_url('/home/dashboard') ?>';
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