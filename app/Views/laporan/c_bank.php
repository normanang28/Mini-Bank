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
            <th class="text-center">Transaksi</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Kredit</th>
            <th class="text-center">Saldo</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $totalDebit = 0;
          $totalKredit = 0;
          $totalSemua = 0;
          $runningBalance = 0;
          $penyetoran = $duar['table1'];

          foreach ($penyetoran as $entry) {
            $totalD += $entry->jumlah_penyetoran + $entry->jumlah_penarikan;
            $totalK += $entry->jumlah_penyetoran + $entry->jumlah_penarikan;

            $totalDebit += $entry->jumlah_penyetoran;
            $totalSemua += $entry->jumlah_penyetoran;
            $runningBalance = $totalSemua;
            ?>
            <tr>
              <td style="text-align: center;"><?php echo $entry->tanggal_penyetoran ?></td>
              <td style="text-align: center;">Penyetoran</td>
              <td>Kas</td>
              <td style="text-align: center;">Rp. <?php echo number_format($entry->jumlah_penyetoran, 0, ',', '.') ?></td>
              <td style="text-align: center;">~</td>
              <td rowspan="2" style="text-align: center;">Rp. <?php echo number_format($runningBalance, 0, ',', '.') ?></td>
            </tr>
            <tr>
              <td colspan="2" class="text-right"></td>
              <td style="text-align: right;">Tabungan ~ <?php echo $entry->nama_nasabah ?> </td>
              <td style="text-align: center;">~</td>
              <td style="text-align: center;">Rp. <?php echo number_format($entry->jumlah_penyetoran, 0, ',', '.') ?></td>
              <!-- <td rowspan="2" style="text-align: center;">Rp. <?php echo number_format($runningBalance, 0, ',', '.') ?></td> -->
            </tr>
            <?php
          }
          ?>
        </tbody>
        <tbody>
          <?php
          $no = 1;
          $penarikan = $duar['table2'];

          foreach ($penarikan as $entry) {
            // Check if status is "Penarikan Berhasil"
            if ($entry->status == "Penarikan Berhasil") {
              $totalD += $entry->jumlah_penyetoran + $entry->jumlah_penarikan;
              $totalK += $entry->jumlah_penyetoran + $entry->jumlah_penarikan;

              $totalKredit += $entry->jumlah_penarikan;
              $totalSemua -= $entry->jumlah_penarikan;
              $runningBalance = $totalSemua;
              ?>
              <tr>
                <td style="text-align: center;"><?php echo $entry->tanggal_penarikan ?></td>
                <td style="text-align: center;">Penarikan</td>
                <td>Tabungan ~ <?php echo $entry->nama_nasabah ?></td>
                <td style="text-align: center;">Rp. <?php echo number_format($entry->jumlah_penarikan, 0, ',', '.') ?></td>
                <td style="text-align: center;">~</td>
                <td rowspan="2" style="text-align: center;">Rp. <?php echo number_format($runningBalance, 0, ',', '.') ?></td>
              </tr>
              <tr>
                <td colspan="2" class="text-right"></td>
                <td style="text-align: right;">Kas</td>
                <td style="text-align: center;">~</td>
                <td style="text-align: center;">Rp. <?php echo number_format($entry->jumlah_penarikan, 0, ',', '.') ?></td>
                <!-- <td rowspan="2" style="text-align: center;">Rp. <?php echo number_format($runningBalance, 0, ',', '.') ?></td> -->
              </tr>
            <?php
            }
          }
          ?>
        </tbody>
        <tr>
          <td colspan="3" style="text-align: center;"><b>Jumlah</b></td>
          <td style="text-align: center;"><b>Rp. <?php echo number_format($totalD, 0, ',','.'); ?></b></td>
          <td style="text-align: center;"><b>Rp. <?php echo number_format($totalK, 0, ',','.'); ?></b></td>
          <td style="text-align: center;"><b>Rp. <?php echo number_format($runningBalance, 0, ',','.'); ?></b></td>
        </tr>
      </table>
    </div>
  </div>

  <script>
    window.print();
  </script>
</body>
</html>
