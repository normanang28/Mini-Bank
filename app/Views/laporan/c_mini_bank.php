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
    }

    table {
      width: 100%; 
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #000;
      padding: 8px;
      /*text-align: center;*/
    }

    th {
      background-color: #f2f2f2;
    }

    td:nth-child(4) {
      text-align: justify;
      text-transform: capitalize;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <!-- <img src="path_to_your_image.jpg" alt="Your Logo"> -->
      <h1>Laporan Transaksi Mini Bank</h1>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Kredit</th>
            <th class="text-center">Saldo</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $key=0;
          $balancePenyetoran = 0;
          $balance = 0;
          $totalPenyetoran = 0;
          $totalPenarikan = 0;
          $penyetoran = $duar['table1'];
          $penarikan = $duar['table2'];
          $totalRows = max(count($penyetoran), count($penarikan));
          for ($i = 1; $i <= $totalRows; $i++) {
            $totalPenyetoran += $penyetoran[$key]->jumlah_penyetoran;
            $totalPenarikan += $penarikan[$key]->jumlah_penarikan;
            $selisih = $totalPenyetoran - $totalPenarikan;
          ?>
            <tr>
              <td style="text-align: center;"><?php echo $penyetoran[$key]->tanggal_penyetoran?></td>
              <td>Kas</td>
              <td style="text-align: center;">Rp. <?php echo number_format($penyetoran[$key]->jumlah_penyetoran, 0, ',', '.') ?></td>
              <td style="text-align: center;">~</td>
              <td style="text-align: center;">Rp. <?php echo number_format($penyetoran[$key]->jumlah_penyetoran, 0, ',', '.') ?></td>
              </tr>
            <tr>
              <td style="text-align: center;"><?php echo $penarikan[$key]->tanggal_penarikan?></td>
              <td style="text-align: right;">Penarikan</td>
              <td style="text-align: center;">~</td>
              <td style="text-align: center;">Rp. <?php echo number_format($penarikan[$key]->jumlah_penarikan, 0, ',', '.') ?></td>
              <td style="text-align: center;">- Rp. <?php echo number_format($penarikan[$key]->jumlah_penarikan, 0, ',', '.') ?></td>
            </tr>
          <?php 
          $key++;
        }?>
        </tbody>
            <tr>
             <td colspan="2" style="text-align: center;"><b>Total</b></td>
             <td style="text-align: center;"><b>Rp. <?php echo number_format($totalPenyetoran, 0, ',', '.') ?></b></td>
             <td style="text-align: center;"><b>Rp. <?php echo number_format($totalPenarikan, 0, ',', '.') ?></b></td>
             <td style="text-align: center;"><b>Rp. <?php echo number_format($selisih, 0, ',', '.') ?></b></td>
           </tr>
      </table>
    </div>
  </div>

  <script>
    window.print();
  </script>
</body>
</html>
