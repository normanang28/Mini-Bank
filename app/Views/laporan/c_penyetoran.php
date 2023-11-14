<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-transform: capitalize;
    }

    .container {
      max-width: 8000px;
      margin: 0 auto;
      padding: 20px;
      text-align: center;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header img {
      width: 100%;
      height: auto;
    }

    .table-container {
      margin-top: 20px;
      text-align: center; 
    }

    table {
      width: 100%; 
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }


  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Transaki Penyetoran</h1>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>No Penyetoran</th>
            <th>NIK</th>
            <th>Nama Nasabah</th>
            <th>Tanggal</th>
            <th>Jenis Penyetoran</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          foreach ($duar as $gas) {
            ?>
            <tr>
              <td><?php echo $no++ ?></td>
              <td>MB-<?php echo $gas->id_penyetoran?>(<?php echo $gas->id_nasabah_penyetoran?><?php echo $gas->maker_penyetoran?>)</td>
              <td><?php echo $gas->nik?></td>
              <td><?php echo $gas->nama_nasabah?></td>
              <td><?php echo $gas->tanggal_penyetoran?></td>
              <td><?php echo $gas->jenis_penyetoran?></td>
              <td>Rp. <?= number_format($gas->jumlah_penyetoran, 0, ',', '.') ?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    window.print();
  </script>
</body>
</html>
