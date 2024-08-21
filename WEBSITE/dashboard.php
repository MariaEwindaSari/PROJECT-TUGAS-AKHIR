<?php

// Periksa apakah pengguna sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}


?>  

<h1>Selamat datang,<?php echo ($username); ?></h1>
<div class="card">
    <div class="card-header text-center bg">
    <h2>Grafik Suhu dan Kelembapan</h2>
    </div>
    <div class="card-body">
        
    <div style="width: 80%; margin: 0 auto;">
        <canvas id="grafik1"></canvas>
    </div>
    <script>
function updateChart() {
    var endpoint = 'grafik_data.php'; // Sesuaikan dengan lokasi file PHP Anda
    var labels = [];
    var suhuData = [];
    var kelembapanData = [];
    var kelembapanTanahData = [];

    fetch(endpoint)
        .then(response => response.json())
        .then(data => {
            data.forEach(row => {
                labels.push(row.created_at);
                suhuData.push(row.suhu);
                kelembapanData.push(row.kelembapan);
                kelembapanTanahData.push(row.kelembapan_tanah);
            });

            // Buat grafik suhu dan kelembapan menggunakan Chart.js
            var ctx = document.getElementById('grafik1').getContext('2d');
            var grafik1 = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Suhu (C)',
                        data: suhuData,
                        borderColor: 'rgba(255, 99, 71)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 3
                    },
                    {
                        label: 'Kelembapan (%)',
                        data: kelembapanData,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 3
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nilai'
                            }
                        }
                    }
                }
            });

            // Buat grafik kelembapan tanah menggunakan Chart.js
            var ctx2 = document.getElementById('grafik2').getContext('2d');
            var grafik2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kelembapan Tanah (%)',
                        data: kelembapanTanahData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 3
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nilai'
                            }
                        }
                    }
                }
            });
        });
}

// Panggil fungsi updateChart untuk pertama kali saat halaman dimuat
updateChart();


setInterval(updateChart, 5000); 
</script>         
    </div>
</div>


<div class="card mb-4" style="margin-top: 20px;">
    <div class="card-header text-center">
        <h2>Grafik Kelembapan Tanah</h2>
    </div>
    <div class="card-body">
        <div style="width: 80%; margin: 0 auto;">
            <canvas id="grafik2"></canvas>
        </div>
    </div>
</div>