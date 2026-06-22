<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERTA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../asset/style.css">
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar p-3">

        <a href="dashboardAdm.html">
            <i class="bi bi-house-door"></i>
        </a>

        <a href="pengguna.html">
            <i class="bi bi-people"></i>
        </a>

        <a href="laporanAdm.html">
            <i class="bi bi-file-earmark-text"></i>
        </a>

        <a href="transaksi.html">
            <i class="bi bi-cart-check"></i>
        </a>

        <hr class="text-white">

        <a href="../auth/login.html">
            <i class="bi bi-box-arrow-right"></i>
        </a>

    </div>

    <!-- CONTENT -->
    <div class="dashboard-container">

        <!-- HEADER -->
        <div class="dashboard-header">

            <h1 class="title">SIPERTA</h1>

        </div>

        <!-- DASHBOARD -->
        <div class="dashboard-content">

            <h2 class="page-title">
                Dashboard Admin
            </h2>

            <div class="stat-row">

                <div class="stat-card">
                    <p>Total Petani</p>
                    <h3 id="totalPetani">25</h3>
                </div>

                <div class="stat-card">
                    <p>Total Pedagang</p>
                    <h3 id="totalPedagang">12</h3>
                </div>

                <div class="stat-card">
                    <p>Total Ahli</p>
                    <h3 id="totalAhli">8</h3>
                </div>

                <div class="stat-card">
                    <p>Total Laporan</p>
                    <h3 id="totalLaporan">4</h3>
                </div>

            </div>

            <!-- AKTIVITAS -->
            <div class="activity-card">

                <h4>Aktivitas Terbaru</h4>

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>01/07/2026</td>
                            <td>Petani A</td>
                            <td>Menambahkan Data Lahan</td>
                            <td>
                                <span class="badge bg-success">
                                    Berhasil
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>02/07/2026</td>
                            <td>Pedagang B</td>
                            <td>Melakukan Pembelian</td>
                            <td>
                                <span class="badge bg-primary">
                                    Selesai
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>03/07/2026</td>
                            <td>Petani C</td>
                            <td>Mengirim Laporan</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    Menunggu
                                </span>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>