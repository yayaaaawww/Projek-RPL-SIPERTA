<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/style.css">
    <title>SIPERTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3">

        <a href="dashboardP.html">
                <i class="bi bi-house-door"></i>
            </a>    

            <a href="konsultasiP.html">
                <i class="bi bi-chat-left"></i>
            </a>

            <a href="laporanP.html">
                <i class="bi bi-exclamation-triangle"></i>
            </a>

            <a href="katalogP.html">    
                <i class="bi bi-cart-plus"></i>
            </a>

            <hr class="text-white">

            <a href="../auth/login.html">
                <i class="bi bi-box-arrow-right"></i>
            </a>

    </div>

        <div class="dashboard-container">

            <!-- HEADER -->
            <div class="dashboard-header">

                <h1 class="title">SIPERTA</h1>
                
               <div class="header-right">

                    <a href="keranjang.html" class="cart-btn">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-badge"></span>
                    </a>

                    <a href="profileP.html" class="profile">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>

                </div>

            </div>

            <div class="dashboard-content">

            <h2 class="page-title">Dashboard</h2>

                <div class="stat-row">

                    <div class="stat-card">
                        <h6>Pesanan Dikemas</h6>
                        <h3>15</h3>
                    </div>

                    <div class="stat-card">
                        <h6>Siap Diambil</h6>
                        <h3>3</h3>
                    </div>

                    <div class="stat-card">
                        <h6>Total Transaksi</h6>
                        <h3>100</h3>
                    </div>

                    <div class="stat-card">
                        <h6>Riwayat Pesanan</h6>
                        <h3>90</h3>
                    </div>

                </div>

                <hr class="text-white">

                 <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-06-01 10:00</td>
                                    <td>Pesanan Dikemas</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>

                                <tr>
                                    <td>2024-06-02 14:30</td>
                                    <td>Pembelian Berhasil</td>
                                    <td><span class="badge bg-success">Sukses</span></td>
                                </tr>

                                <tr>
                                    <td>2024-06-03 09:15</td>
                                    <td>Barang Siap Diambil</td>
                                    <td><span class="badge bg-danger">Gagal</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>