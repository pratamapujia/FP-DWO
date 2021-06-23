<!DOCTYPE html>
<html lang="en">


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>FP | DWO</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/izitoast/css/iziToast.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/line-chart.png' />
  <!-- Chart JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
  <!-- Highchart -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>

<body>
  <?php
  session_start();
  if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
  }
  ?>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="javascript:void(0)" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="menu"></i></a></li>
            <li><a href="javascript:void(0)" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="javascript:void(0)" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="assets/img/profile.svg" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Welcome My APES</div>
              <div class="dropdown-divider"></div>
              <a href="logout.php" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.php"> <img alt="image" src="assets/img/line-chart.svg" class="header-logo" /> <span class="logo-name">FP | DWO</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
              <a href="index.php" class="nav-link"><i class="fas fa-desktop"></i><span>Example</span></a>
            </li>
            <li class="dropdown">
              <a href="chart1.php" class="nav-link"><i class="fas fa-chart-area"></i><span>Chart 1</span></a>
            </li>
            <li class="dropdown active">
              <a href="chart2.php" class="nav-link active"><i class="fas fa-chart-area"></i><span>Chart 2</span></a>
            </li>
            <li class="dropdown">
              <a href="chart3.php" class="nav-link"><i class="fas fa-chart-area"></i><span>Chart 3</span></a>
            </li>
          </ul>
        </aside>
      </div>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4>Menampilkan Top 10 customer dari Peminjaman Film</h4>
                  </div>
                  <div class="card-body">
                    <div id="container"></div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card card-danger" style="height:600px;">
                  <div class="card-header">
                    <h4>Mondrian</h4>
                  </div>
                  <div class="card-body">
                    <iframe src="http://localhost:8080/mondrian/index.html" frameborder="0" style="height:100%; width:100%; border:none;  "></iframe>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php require('koneksi.php'); ?>
          <?php
          $sql = mysqli_query($conn, "SELECT count(customer_id) as total FROM fakta_pendapatan");
          $total = mysqli_fetch_row($sql);
          //print_r($total);

          $sqli = "SELECT concat('name:', s.nama_kota) as name, concat('y:',count(distinct(c.nama))) as y, concat('drilldown:', s.nama_kota) as drilldown FROM fakta_pendapatan fp JOIN store s ON fp.store_id=s.store_id JOIN customer c ON fp.customer_id = c.customer_id GROUP BY fp.store_id";
          $sum_toko = mysqli_query($conn, $sqli);
          while ($row = mysqli_fetch_all($sum_toko)) {
            $data[] = $row;
          }
          $json_sum_toko = json_encode($data);
          //echo $json_sum_toko;

          $data0 = $json_sum_toko;
          $data1 = str_replace('[["', '{"', $data0);
          $data2 = str_replace('"]]', '"}', $data1);
          $data3 = str_replace('],[', '},{', $data2);
          $data4 = str_replace('"name:', 'name: "', $data3);
          $data5 = str_replace(',"y:', ',y: ', $data4);
          $data6 = str_replace('","drilldown:', ',drilldown: "', $data5);
          //echo $data6;

          $top1 = "SELECT c.nama, SUM(fp.lamapinjam) AS score FROM fakta_pendapatan fp JOIN customer c ON fp.customer_id = c.customer_id WHERE fp.store_id = 1 GROUP BY c.nama ORDER BY score DESC LIMIT 10";
          $hasil1 = mysqli_query($conn, $top1);

          while ($row = mysqli_fetch_all($hasil1)) {
            $dataTop = $row;
          }
          $jsonTop1 = json_encode($dataTop);
          //echo $jsonTop;

          $Lethbridge = $jsonTop1;
          $Lethbridge1 = str_replace('","', '",', $Lethbridge);
          $Lethbridge2 = str_replace('"]', ']', $Lethbridge1);
          //echo $Lethbridge2;

          $top2 = "SELECT c.nama, SUM(fp.lamapinjam) AS score FROM fakta_pendapatan fp JOIN customer c ON fp.customer_id = c.customer_id WHERE fp.store_id = 2 GROUP BY c.nama ORDER BY score DESC LIMIT 10";
          $hasil2 = mysqli_query($conn, $top2);

          while ($row = mysqli_fetch_all($hasil2)) {
            $dataTop = $row;
          }
          $jsonTop2 = json_encode($dataTop);
          //echo $jsonTop;

          $Woodridge = $jsonTop2;
          $Woodridge1 = str_replace('","', '",', $Woodridge);
          $Woodridge2 = str_replace('"]', ']', $Woodridge1);
          //echo $Woodridge2;
          ?>
        </section>
      </div>

      <script type="text/javascript">
        // Create the chart
        Highcharts.chart('container', {
          chart: {
            type: 'column'
          },
          title: {
            text: 'Top 10 Customer Paling Lama Meminjam Film'
          },
          subtitle: {
            text: 'dikategorikan berdasarkan toko'
          },
          accessibility: {
            announceNewData: {
              enabled: true
            }
          },
          xAxis: {
            type: 'category'
          },
          yAxis: {
            title: {
              text: 'Total'
            }

          },
          legend: {
            enabled: false
          },
          plotOptions: {
            series: {
              borderWidth: 0,
              dataLabels: {
                enabled: true,
                format: '{point.y:1f}'
              }
            }
          },

          tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}</b><br/>'
          },

          series: [{
            name: "Total customer di",
            colorByPoint: true,
            data: <?= $data6; ?>
          }],
          drilldown: {
            series: [{
                name: "Lethbridge",
                id: "Lethbridge",
                data: <?= $Lethbridge2; ?>
              },
              {
                name: "Woodridge",
                id: "Woodridge",
                data: <?= $Woodridge2; ?>
              }
            ]
          }
        });
      </script>
      <footer class="main-footer">
        <div class="footer-left">
          &copy; <?= DATE('Y'); ?> <a href=""> TIM APES </a>
        </div>
      </footer>
    </div>
  </div>
  <!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
  <!-- <script src="assets/bundles/chartjs/chart.min.js"></script> -->
  <!-- Page Specific JS File -->
  <script src="assets/js/page/toastr.js"></script>
  <!-- <script src="assets/js/page/chart-chartjs.js"></script> -->
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
</body>


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

</html>