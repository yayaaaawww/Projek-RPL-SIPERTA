<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIPERTA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../asset/style.css">
</head>

<body>

<div class="d-flex">

    <div class="sidebar p-3">

        <a href="dashboardAdmin.html">
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

    <div class="dashboard-container">

        <div class="dashboard-header">

            <h1 class="title">
                SIPERTA
            </h1>

        </div>

        <div class="dashboard-content">

            <h2 class="page-title">
                Data Laporan
            </h2>

            <div class="activity-card">

                <table class="table table-hover">

                    <thead>

                        <tr>
                            <th>Pelapor</th>
                            <th>Terlapor</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody id="laporanTable">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="modalDetail">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5>Detail Laporan</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    <strong>Pelapor :</strong>
                    <span id="detailPelapor"></span>
                </p>

                <p>
                    <strong>Terlapor :</strong>
                    <span id="detailTerlapor"></span>
                </p>

                <p>
                    <strong>Alasan :</strong>
                </p>

                <p id="detailAlasan"></p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>

let laporan =
JSON.parse(
    localStorage.getItem("laporan")
) || [];

const table =
document.getElementById("laporanTable");

function tampilkanLaporan()
{
    table.innerHTML = "";

    laporan.forEach((item,index)=>{

        table.innerHTML += `
        <tr>

            <td>${item.pelapor}</td>

            <td>${item.terlapor}</td>

            <td>${item.role}</td>

            <td>

                <span class="badge bg-warning text-dark">
                    Baru
                </span>

            </td>

            <td>

                <button
                    class="btn btn-primary btn-sm"
                    onclick="lihatDetail(${index})">

                    Detail

                </button>

            </td>

        </tr>
        `;
    });
}

tampilkanLaporan();

function lihatDetail(index)
{
    document.getElementById("detailPelapor")
    .innerHTML = laporan[index].pelapor;

    document.getElementById("detailTerlapor")
    .innerHTML = laporan[index].terlapor;

    document.getElementById("detailAlasan")
    .innerHTML = laporan[index].alasan;

    new bootstrap.Modal(
        document.getElementById("modalDetail")
    ).show();
}

</script>

</body>
</html>