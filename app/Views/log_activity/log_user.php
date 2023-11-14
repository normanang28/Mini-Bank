<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="header-left">
            <form action="<?= base_url('home/log_user_search') ?>" method="post">
                    <div class="input-group search-area">
                        <input type="text" class="form-control text-capitalize" name="search_log_user" placeholder="Search here...">
                        <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                    </div>
                </form>
            </div>
            <br>
            <div class="right-aligned">
                <?php if(!empty($search)) {?>
                    <a href="<?= base_url('/home/log_activity_user/')?>"><button class="btn btn-info"><i class="fa fa-reply"></i> Back</button></a>
                <?php }?>
            </div>
            <br>
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
           <div class="table-responsive">
            <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Aktivitas</th>
                        <th class="text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no=1;

                    foreach ($duar as $gas){
                      ?>
                      <tr>
                          <th class="text-center"><?php echo $no++ ?></th>
                          <td class="text-capitalize text-center"><?php echo $gas->username?></td>      
                          <td class="text-capitalize text-center"><?php echo $gas->activity?></td>
                          <td class="text-capitalize text-center"><?php echo $gas->tanggal_activity?></td>
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

            .button-container {
                display: flex;
                justify-content: center; 
                align-items: center;
            }

            .button-container a {
                margin: 0 5px; 
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
            <td class="text-capitalize text-center text-dark">${gas.username}</td>
            <td class="text-capitalize text-center text-dark">${gas.activity}</td>
            <td class="text-capitalize text-center">${gas.tanggal_activity}</td>
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