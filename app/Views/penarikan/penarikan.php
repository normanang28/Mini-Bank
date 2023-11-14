<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<!-- Nav tabs -->
<div class="default-tab">
<ul class="nav nav-tabs" role="tablist">
<li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#home"><i class="fa-solid fa-landmark me-2"></i> Proses Pencairan</a>
</li>
<li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#profile"><i class="fa-solid fa-hand-holding-dollar me-2"></i> Pencairan Dana</a>
</li>
</ul>
<div class="tab-content">
<div class="tab-pane fade show active" id="home" role="tabpanel">
    <div class="pt-4">
       
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
    <?php if(session()->get('level')== 4) { ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-exclamation-triangle blinking-icon"></i> Jika nomor penarikan ditampilkan dalam warna "merah," ini menandakan bahwa dana Anda sedang dalam proses pencairan. Pada tab "Pencairan Dana," Anda akan menemukan kumpulan data pencairan dana yang telah berhasil atau telah selesai.
        </div>
    <?php }else{} ?>
        <div class="header-left">
            <form action="<?= base_url('home/penarikan_search') ?>" method="post">
                <div class="input-group search-area">
                    <input type="text" class="form-control text-capitalize" name="search_penarikan" placeholder="Search here...">
                    <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                </div>
            </form>
        </div>
    <form action="<?= base_url('/home/status/')?>" method="post">
        <div class="right-aligned">
            <?php if(!empty($search)) {?>
                <a href="<?= base_url('/home/penarikan/')?>"><button type="button" class="btn btn-info"><i class="fa fa-reply"></i> Back</button></a>
            <?php }?>
            <?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i></button>
            <?php }else{} ?>
            <?php if(session()->get('level')== 4) { ?>
            <a href="<?= base_url('/home/tambah_penarikan/')?>"><button type="button" class="btn btn-success custom-button"><i class="fa fa-plus"></i> Tambah</button></a>
            <?php }else{} ?>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-print me-2"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" target="_blank" href="<?= base_url('/home/print_penarikan')?>">Print</a>
                    <a class="dropdown-item" target="_blank" href="<?= base_url('/home/pdf_penarikan')?>">PDF</a>
                    <a class="dropdown-item" href="<?= base_url('/home/excel_penarikan')?>">Excel</a>
                </div>
            </div>
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
        <style>
            .search-area input::placeholder {
                color: #999; 
                transition: color 0.3s; 
            }

            .search-area input:focus::placeholder {
                color: #ff0000;
            }
        </style>
        <br>
        <div class="table-responsive">
            <table class="table items-table table table-bordered table-striped verticle-middle table-responsive-sm">
                <thead>
                    <tr>
            <?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
                        <th class="text-center">#</th>
            <?php }else{} ?>
            <?php if(session()->get('level')== 4) { ?>
                        <th class="text-center">No</th>
            <?php }else{} ?>
                        <th class="text-center">No Penarikan</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">Nama Nasabah</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Jenis Penarikan</th>
                        <th class="text-center">Jumlah</th>
            <?php if(session()->get('level')== 4) { ?>
                        <th class="text-center">Keterangan</th>
            <?php }else{} ?>
            <?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
                        <th class="text-center">Action</th>
            <?php }else{} ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no=1;

                    function ubahFormatTanggal($tanggal_asli) {
                        return date("d F Y", strtotime($tanggal_asli));
                    }

                    foreach ($duar as $gas){
                        if ($gas->status != "Penarikan Berhasil") { 
                      ?>
                      <tr>
            <?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
                        <td class="text-center">
                            <input type="checkbox" class="checkbox__input" value="<?= $gas->id_penarikan ?>" name="penarikan[]" id="penarikan_<?= $gas->id_penarikan ?>"/>
                        </td>
            <?php }else{} ?> 
            <?php if(session()->get('level')== 4) { ?>
                        <th class="text-center"><?php echo $no++ ?></th>
            <?php }else{} ?> 
                        <td class="text-center text-capitalize text-dark" style="color: red !important;">PPD-<?php echo $gas->id_penarikan?>(<?php echo $gas->id_nasabah_penarikan?><?php echo $gas->maker_penarikan?>)</td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->nik?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->nama_nasabah?></td>
                        <td class="text-center text-capitalize text-dark"><?= ubahFormatTanggal($gas->tanggal_penarikan) ?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->jenis_penarikan?></td>
                        <td class="text-center text-capitalize text-dark">Rp. <?= number_format($gas->jumlah_penarikan, 0, ',', '.') ?></td>
            <?php if(session()->get('level')== 4) { ?>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->keterangan_penarikan?></td>
            <?php }else{} ?>
            <?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
                        <td>
                            <div class="col-12 center-column">
                              <a href="<?= base_url('/home/detail_penarikan/'.$gas->id_penarikan )?>"><button class="btn btn-info" type="button"><i class="fa fa-bars"></i> Details</button></a>
                          </div>
                      </td>
            <?php }else{} ?>
                  </tr>
              <?php }}?>
          </tbody>
      </table>
    <style>
     .pagination {
        display: flex;
        justify-content: flex-end; 
        align-items: center; 
    }

    .page-numbers button {
        margin-left: 5px; 
        font-size: 14px; 
        padding: 5px 10px;
    }

    .center-column {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .center-column .btn {
        margin-top: 5px; 
    }

    .button-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
</div>
</form>

    </div>
</div>
<div class="tab-pane fade" id="profile">
    <div class="pt-4">
        
        <div class="table-responsive">
            <table class="table items-table table table-bordered table-striped verticle-middle table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No Penarikan</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">Nama Nasabah</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Jenis Penarikan</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Keterangan</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no=1;

                    foreach ($duar as $gas){
                        if ($gas->status == "Penarikan Berhasil") { 
                      ?>
                      <tr>
                        <th class="text-center"><?php echo $no++ ?></th>
                        <td class="text-center text-capitalize text-dark">PPD-<?php echo $gas->id_penarikan?>(<?php echo $gas->id_nasabah_penarikan?><?php echo $gas->maker_penarikan?>)</td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->nik?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->nama_nasabah?></td>
                        <td class="text-center text-capitalize text-dark"><?= ubahFormatTanggal($gas->tanggal_penarikan) ?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->jenis_penarikan?></td>
                        <td class="text-center text-capitalize text-dark">Rp. <?= number_format($gas->jumlah_penarikan, 0, ',', '.') ?></td>
                        <td class="text-center text-capitalize text-dark"><?php echo $gas->keterangan_penarikan?></td>
                  </tr>
              <?php }}?>
          </tbody>
      </table>
</div>

    </div>
</div>
</div>
</div>
</div>
</div>
</div>
