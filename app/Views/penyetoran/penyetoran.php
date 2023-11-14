<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="header-left">
                <form action="<?= base_url('home/penyetoran_search') ?>" method="post">
                    <div class="input-group search-area">
                        <input type="text" class="form-control text-capitalize" name="search_penyetoran" placeholder="Search here...">
                        <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                    </div>
                </form>
            </div>
            <div class="right-aligned">
                <?php if(!empty($search)) {?>
                    <a href="<?= base_url('/home/penyetoran/')?>"><button class="btn btn-info"><i class="fa fa-reply"></i> Back</button></a>
                <?php }?>
                <?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
                <a href="<?= base_url('/home/tambah_penyetoran/')?>"><button class="btn btn-success custom-button"><i class="fa fa-plus"></i> Tambah</button></a>
                <?php }else{} ?>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-print me-2"></i></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" target="_blank" href="<?= base_url('/home/print_penyetoran')?>">Print</a>
                        <a class="dropdown-item" target="_blank" href="<?= base_url('/home/pdf_penyetoran')?>">PDF</a>
                        <a class="dropdown-item" href="<?= base_url('/home/excel_penyetoran')?>">Excel</a>
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
                            <th class="text-center">No</th>
                            <th class="text-center">No Penyetoran</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Nama Nasabah</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jenis Penyetoran</th>
                            <th class="text-center">Jumlah</th>
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
                          ?>
                          <tr>
                            <th class="text-center"><?php echo $no++ ?></th>
                            <td class="text-center text-capitalize text-dark">MB-<?php echo $gas->id_penyetoran?>(<?php echo $gas->id_nasabah_penyetoran?><?php echo $gas->maker_penyetoran?>)</td>
                            <td class="text-center text-capitalize text-dark"><?php echo $gas->nik?></td>
                            <td class="text-center text-capitalize text-dark"><?php echo $gas->nama_nasabah?></td>
                            <td class="text-center text-capitalize text-dark"><?= ubahFormatTanggal($gas->tanggal_penyetoran) ?></td>
                            <td class="text-center text-capitalize text-dark"><?php echo $gas->jenis_penyetoran?></td>
                            <td class="text-center text-capitalize text-dark">Rp. <?= number_format($gas->jumlah_penyetoran, 0, ',', '.') ?></td>
<?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
                            <td>
                                <div class="col-12 center-column">
                                  <a href="<?= base_url('/home/detail_penyetoran/'.$gas->id_penyetoran )?>"><button class="btn btn-info"><i class="fa fa-bars"></i> Details</button></a>
                              </div>
                          </td>
<?php }else{} ?>
                      </tr>
                  <?php }?>
              </tbody>
          </table>
      </div>
      <div class="pagination">
        <nav>
            <ul class="pagination pagination-sm">
                <li class="page-item page-indicator" id="previousPageButton">
                    <a class="page-link" href="javascript:void(0)">
                        <i class="la la-angle-left"></i></a>
                    </li>
                    <li class="page-item" id="currentPageNumber">1</li>
                    <li class="page-item page-indicator" id="nextPageButton">
                        <a class="page-link" href="javascript:void(0)">
                            <i class="la la-angle-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tableBody = document.querySelector('.table tbody');
                const currentPageNumber = document.getElementById('currentPageNumber');
                const previousPageButton = document.getElementById('previousPageButton');
                const nextPageButton = document.getElementById('nextPageButton');

    const data = <?= json_encode($duar) ?>; 
    const itemsPerPage = 20;
    let currentPage = 1;
    const totalPages = Math.ceil(data.length / itemsPerPage);

    function displayDataOnPage(page) {
        tableBody.innerHTML = ''; 

        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        for (let i = startIndex; i < endIndex && i < data.length; i++) {
            const gas = data[i];
            const row = `
            <tr>
            <th class="text-center">${i + 1}</th>
            <td class="text-center text-capitalize text-dark">MB-${gas.id_penyetoran}(${gas.id_nasabah_penyetoran}${gas.maker_penyetoran})</td>
            <td class="text-center text-capitalize text-dark">${gas.nik}</td>
            <td class="text-center text-capitalize text-dark">${gas.nama_nasabah}</td>
            <td class="text-center text-capitalize text-dark">${ ubahFormatTanggal gas.tanggal_penyetoran}</td>
            <td class="text-center text-capitalize text-dark">${gas.jenis_penyetoran}</td>
            <td class="text-center text-capitalize text-dark">Rp. ${ubahFormatAngka(gas.jumlah_penyetoran)}</td>
<?php if(session()->get('level')== 1 || session()->get('level')== 2) { ?>
            <td>
                <div class="col-12 center-column">
                    <a href="<?= base_url('/home/detail_penyetoran/') ?>/${gas.id_penyetoran}"><button class="btn btn-info"><i class="fa fa-bars"></i> Details</button></a>
                </div>
            </td>
<?php }else{} ?>
            </tr>
            `;
            tableBody.innerHTML += row;
        }
    }

    function updatePageNumbers() {
        currentPageNumber.textContent = currentPage;
    }

    previousPageButton.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            displayDataOnPage(currentPage);
            updatePageNumbers();
        }
    });

    nextPageButton.addEventListener('click', function () {
        if (currentPage < totalPages) {
            currentPage++;
            displayDataOnPage(currentPage);
            updatePageNumbers();
        }
    });

    displayDataOnPage(currentPage);
    updatePageNumbers();
});

</script>
</div>
</div>
</div>