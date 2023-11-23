<div class="row">
    <div class="col-xl-4 col-xxl-4 col-lg-7 col-md-7 col-sm-7">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon d-flex">  
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </span>
                    <div class="media-body">
                        <h4 class="mb-0 text-black"><span class="ms-0"><?= 'Rp. ' . number_format($penyetoran['jumlah_penyetoran'], 0, ',', '.') ?></span></h4>
                        <p class="mb-0">Total Penyetoran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-xxl-4 col-lg-7 col-md-7 col-sm-7">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon d-flex">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-comments-dollar"></i>
                    </span>
                    <div class="media-body">
                        <h4 class="mb-0 text-black"><span class=" ms-0"><?= 'Rp. ' . number_format($penarikan['jumlah_penarikan'], 0, ',', '.') ?></span></h4>
                        <p class="mb-0">Total Penarikan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-xxl-4 col-lg-7 col-md-7 col-sm-7">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon d-flex">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                    <div class="media-body">
                        <h4 class="mb-0 text-black"><span class="ms-0">
                            <?php
                            $selisih = $penyetoran['jumlah_penyetoran'] - $penarikan['jumlah_penarikan'];
                            echo 'Rp. ' . number_format($selisih, 0, ',', '.');
                            ?>
                        </span></h4>
                        <p class="mb-0">Total Saldo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .widget-stat.card {
        border: 1px solid #f1f1f1;
        transition: box-shadow 0.3s, transform 0.3s; 
        border-radius: 20px;
        overflow: hidden;
    }

    .widget-stat.card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: scale(1.05); 
    }

    .widget-stat.card .card-body {
        padding: 16px;
        transition: background-color 0.3s; 
    }

    .widget-stat.card .media-body h4 {
        overflow: hidden;
        text-overflow: ellipsis; 
        white-space: nowrap; 
        max-height: 60px; 
        margin-bottom: 8px; 
        font-size: 1.2rem;
        transition: font-size 0.3s; 
    }

    .widget-stat.card .media-body {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .widget-stat.card:hover .media-body h4 {
        font-size: 1.5rem; 
    }

    @media (max-width: 767px) {
        .widget-stat.card:hover {
            transform: none; 
        }
    }
</style>

<style>
    .styled-iframe {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 16px rgba(0, 0, 0, 0.1);
      width: 100%; 
      height: 400px;
      transition: box-shadow 0.3s ease-in-out; 
      border: none; 
    }

    .styled-iframe:hover {
      box-shadow: 0 0 24px rgba(0, 0, 0, 0.2); 
    }

    @media (max-width: 768px) {
      .styled-iframe {
        height: 300px;
      }
    }
  </style>

<iframe class="styled-iframe" 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.051366277353!2d104.01296077472418!3d1.1234472988657864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98966f5876889%3A0xeb151aeee8904615!2sSekolah%20Permata%20Harapan%20Batu%20Batam!5e0!3m2!1sen!2sid!4v1700019911834!5m2!1sen!2sid"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
</iframe>
