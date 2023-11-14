<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="card-body">
            <div class="basic-form">
                <form class="form-horizontal form-label-left" novalidate  action="<?= base_url('home/aksi_ganti_website')?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $use->id_settings ?>">

                    <div class="row">
                        <div class="input-group mb-3">
                            <label class="control-label col-12">Replace New Logo<span style="color: red;">*</span></label> 
                            <div class="col-12 form-file">
                                <input type="file" name="logo" class="form-file-input form-control col-12">
                            </div>
                            <span class="input-group-text">Upload</span>
                        </div>

                        <div class="input-group mb-3">
                            <label class="control-label col-12">Replace New Icon<span style="color: red;">*</span></label> 
                            <div class="col-12 form-file">
                                <input type="file" name="icon" class="form-file-input form-control col-12">
                            </div>
                            <span class="input-group-text">Upload</span>
                        </div>

                        <div class="input-group mb-3">
                            <label class="control-label col-12">Replace New Text<span style="color: red;">*</span></label> 
                            <div class="col-12 form-file">
                                <input type="file" name="text" class="form-file-input form-control col-12">
                            </div>
                            <span class="input-group-text">Upload</span>
                        </div>

                        <div class="input-group mb-3">
                            <label class="control-label col-12">Replace New Background Login Image<span style="color: red;">*</span></label> 
                            <div class="col-12 form-file">
                                <input type="file" name="login" class="form-file-input form-control col-12">
                            </div>
                            <span class="input-group-text">Upload</span>
                        </div>

                        <div class="item form-group">
                            <label class="form-label">Change Website Name<span style="color: red;">*</span></label>
                            <input type="text" id="nama_website" name="nama_website" 
                            class="form-control" placeholder="Change Website Name" value="<?= $use->nama_website?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
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