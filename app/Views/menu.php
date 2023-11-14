        <div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">
<?php  if(session()->get('id')>0) { ?>
                    <li><a href="<?= base_url('/home/dashboard')?>" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-home" title="Dashboard"></i>
                            <span  class="nav-text">Dashboard</span>
                        </a>
                    </li>
<?php }else{} ?>
<?php  if(session()->get('level')== 1 || session()->get('level')== 3) { ?>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-user-9" title="User"></i>
                            <span class="nav-text">User</span>
                        </a>
                        <ul aria-expanded="false">
<?php  if(session()->get('level')== 1) { ?>
                            <li><a href="<?= base_url('/home/pegawai')?>">Data Pegawai</a></li>
<?php }else{} ?>
<?php  if(session()->get('level')== 1 || session()->get('level')== 3) { ?>
                            <li><a href="<?= base_url('/home/nasabah')?>">Data Nasabah</a></li>
<?php }else{} ?>
                        </ul>
<?php }else{} ?>
                    </li>
<?php  if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 4) { ?>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-id-card" title="Transaksi"></i>
                            <span class="nav-text">Transaksi</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="<?= base_url('/home/penyetoran')?>">Transaksi Penyetoran</a></li>
                            <li><a href="<?= base_url('/home/penarikan')?>">Transaksi Penarikan</a></li>
                        </ul>
                    </li>
<?php }else{} ?>
<?php  if(session()->get('level')== 1 || session()->get('level')== 3 || session()->get('level')== 4) { ?>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <i class="flaticon-381-notepad" title="Report"></i>
                            <span class="nav-text">Laporan</span>
                        </a>
                        <ul aria-expanded="false">
                            <!-- <li><a href="<?= base_url('/home/laporan_penyetoran')?>">Laporan Penyetoran</a></li> -->
                            <!-- <li><a href="<?= base_url('/home/laporan_penarikan')?>">Laporan Penarikan</a></li> -->
                            <li><a href="<?= base_url('/home/laporan_mini_bank')?>">Laporan Mini Bank</a></li>
                        </ul>
                    </li>
<?php }else{} ?>
<?php  if(session()->get('level')== 1) { ?>
                    <hr class="sidebar-divider">
                    <li><a href="<?= base_url('/home/log_activity_user')?>" class="ai-icon" aria-expanded="false">
                            <i class="fa-solid fa-people-group" title="Settings Control"></i>
                            <span class="nav-text">Log Avtivity User</span>
                        </a>
                    </li>
                    <li><a href="<?= base_url('/home/settings_control')?>" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-381-settings-4" title="Settings Control"></i>
                            <span class="nav-text">Settings Control</span>
                        </a>
                    </li>
<?php }else{} ?>
                </ul>
            </div>
        </div>
<div class="content-body">
            <div class="container-fluid">
                <div class="form-head d-flex mb-3 align-items-start">
                   <div class="me-auto d-none d-lg-block">
                        <?php
                        $level = session()->get('level'); 
                        $nama_pegawai = session()->get('nama_pegawai');
                        $nama_nasabah = session()->get('nama_nasabah');
                        
                        $userLevelText = "";
                        
                        if ($level == 1) {
                            $userLevelText = "Admin";
                        } elseif ($level == 2) {
                            $userLevelText = "Teller";
                        } elseif ($level == 3) {
                            $userLevelText = "Customer Service";
                        } else {
                            $userLevelText = "Nasabah";
                        }

                        $namaToShow = $nama_pegawai ? $nama_pegawai : $nama_nasabah;
                        
                        echo "<p  class='mb-0 text-capitalize'>Welcome <b>$namaToShow / $userLevelText</b> to " . session()->get('nama_website') . "!</p>";
                        ?>
                    </div>
                    <b><span id="currentDateTime"></span></b>
                </div>


<script>
function updateDateTime() {
    const dateTimeElement = document.getElementById('currentDateTime');
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true, 
    };

    const currentDateTime = new Date().toLocaleString(undefined, options);
    dateTimeElement.textContent = currentDateTime.replace(',', ' at');
}

setInterval(updateDateTime, 1000);

updateDateTime();
</script>


               