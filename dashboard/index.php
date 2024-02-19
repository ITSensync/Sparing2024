<?php
include "../koneksi/koneksi.php";
ini_set('display_errors', 1);
$waktu = date("Y-m-d");

$query = "SELECT * FROM maintb ORDER BY `time` DESC LIMIT 1";
$ambil = mysqli_query($conn, $query);
$data = mysqli_fetch_array($ambil);


$sumdebit = "SELECT sum(debit2) as total_debit FROM maintb WHERE `time` LIKE '%$waktu%' ";
$result = mysqli_query($conn, $sumdebit);
$total_debit = mysqli_fetch_array($result);

// query chartjs
$queryChart = "SELECT `time`, `cod`, `tss`, ph, nh3n, debit2 FROM maintb WHERE DATE(`time`) = CURDATE() ORDER BY `time` ASC";
$resultChart = mysqli_query($conn, $queryChart);

$chartData = [];
while ($rowChart = mysqli_fetch_assoc($resultChart)) {
    $chartData[] = $rowChart;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <title>DASHBOARD SPARING</title>
</head>

<body onload="clock();" onunload="stop();">
    <page size="A">
    <!-- header -->
        <div id="header">
            <div class="row">
                <div class="col-sm-12 col-lg-12 h-100  d-flex align-items-center justify-content-center">
                    <div class="container-fluid">
                        <div class="col-sm-4 ">
                            <h2 onclick="toggleFullScreen()">SPARING</h2>
                        </div>

                    </div>

                    <div id="heading" class="col-sm-4 text-center">
                        <p>PMT</p>
                    </div>

                    <div id="signal" class="col-sm-4 tex-left">
                        <?php
                        //userdefined function for checking internet
                        function check_internet($domain)
                        {
                            $file = @fsockopen($domain, 80); //@fsockopen is used to connect to a socket

                            return ($file);
                        }

                        $domain = "www.google.com";

                        if (check_internet($domain)) {
                            echo "<h2> <img src = 'assets/img/wifi-on.svg'</h2>";
                        } else {
                            echo "<h2> <img src = 'assets/img/wifi-off.svg'</h2>";
                        }
                        ?>

                        <div class="waktu">
                            <div id="date"></div>
                            <div id="time"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end header -->


        <!-- card content -->
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-lg-2">
                    <div class="content">
                        <dtitle>COD</dtitle>
                        <hr>
                        <h2><?= $data['cod']; ?></h2>
                        <strong><span>mg/L</span></strong>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="content">
                        <dtitle>TSS</dtitle>
                        <hr>
                        <h2><?= $data['tss']; ?></h2>
                        <strong><span>mg/L</span></strong>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="content">
                        <dtitle>NH3N</dtitle>
                        <hr>
                        <h2><?= $data['nh3n']; ?></h2>
                        <strong><span>mg/L</span></strong>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="content">
                        <dtitle>PH</dtitle>
                        <hr>
                        <h2><?= $data['ph']; ?></h2>
                        <!-- <strong><span>mg/L</span></strong> -->
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="content">
                        <dtitle>DEBIT</dtitle>
                        <hr>
                        <h2><?= $data['debit2']; ?></h2>
                        <strong><span> per 2 menit</span></strong>
                    </div>
                </div>

                <div class="col-sm-2 col-lg-2">
                    <div class="content">
                        <dtitle>TOTAL DEBIT</dtitle>
                        <hr>
                        <h2><?= $total_debit['total_debit']; ?></h2>
                        <strong><span> per hari</span></strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container -->


        <!-- chart -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card mb-5 bg-secondary">
                        <div class="card-header">
                            GRAFIK NILAI SPARING
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" height="65"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </page>
</body>

</html>
<!-- 
<script src="assets/js/custom.js"></script> -->
<script src="assets/js/chart.js"></script>
<script src="assets/js/jquery-3.5.1.slim.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>


<script type="text/javascript">
   setTimeout(function() {
      window.location.href = window.location;
   }, 120000);
</script>


<script>
    function updateTime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // Formatting the time
        var formattedTime = `${hours}:${minutes}:${seconds}`;

        // Displaying the time in the 'time' div
        document.getElementById('time').innerText = formattedTime;

        // Getting day and date
        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var day = days[now.getDay()];

        var date = now.getDate();

        var months = ['Januari', 'Februari', 'Maret', 'April', 'May', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        var month = months[now.getMonth()]; // Note: January is 0, so we add 1
        var year = now.getFullYear();

        // Formatting the date
        var formattedDate = `${day}, ${date} ${month} ${year}`;


        // Displaying the date in the 'date' div
        document.getElementById('date').innerText = formattedDate;

        // Update time every second
        setTimeout(updateTime, 1000);
    }
    updateTime();


    // Create chart using data from database
    var ctxChart = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctxChart, {
        type: 'line', // Ganti sesuai jenis grafik yang diinginkan (line, bar, dll.)
        data: {
            labels: <?= json_encode(array_map(function ($datetime) {
                        // Extract only the time part from the datetime
                        return date('H:i', strtotime($datetime));
                    }, array_column($chartData, 'time'))) ?>,
            datasets: [{
                    label: 'COD',
                    data: <?= json_encode(array_column($chartData, 'cod')) ?>,
                    backgroundColor: '#ffb91d',
                    borderColor: '#ffb91d',

                },
                {
                    label: 'TSS',
                    data: <?= json_encode(array_column($chartData, 'tss')) ?>,
                    backgroundColor: '#f97817',
                    borderColor: '#f97817',

                },
                {
                    label: 'NH3N',
                    data: <?= json_encode(array_column($chartData, 'nh3n')) ?>,
                    backgroundColor: '#6de304',
                    borderColor: '#6de304',

                },
                {
                    label: 'PH',
                    data: <?= json_encode(array_column($chartData, 'ph')) ?>,
                    backgroundColor: '#ff0000',
                    borderColor: '#ff0000',

                },
                {
                    label: 'DEBIT',
                    data: <?= json_encode(array_column($chartData, 'debit')) ?>,
                    backgroundColor: '#732bea',
                    borderColor: '#732bea',

                },

            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'white' // Set tick color to white
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)' // Set gridline color to white
                    }
                },
                x: {
                    ticks: {
                        color: 'white' // Set tick color to white
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)' // Set gridline color to white
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Set legend label color to white
                    }
                },
                tooltip: {
                    enabled: true,
                    titleColor: 'white', // Set tooltip title color to white
                    bodyColor: 'white' // Set tooltip body color to white
                },
                title: {
                    font: {
                        color: 'white' // Set title font color to white
                    }
                }
            }
        }
    });
</script>