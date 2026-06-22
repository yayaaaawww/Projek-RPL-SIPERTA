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

    <!-- CONTENT -->
    <div class="dashboard-container">

        <div class="dashboard-header">
            <h1 class="title">SIPERTA</h1>

            <div class="header-right">

                <a href="katalogP.html" class="cart-btn">
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

            <h2 class="page-title">Keranjang </h2>

            <div id="keranjangList">  </div>

            <div class="total-card">

                <h4>
                    Total :
                    <span id="totalHarga">
                        Rp 0
                    </span>
                </h4>

                <button
                    class="btn btn-success"
                    onclick="window.location.href='checkout.html'">

                    Checkout

                </button>

            </div>

        </div>

    </div>

</div>

<script>

const keranjang =
JSON.parse(localStorage.getItem("keranjang")) || [];

const list =
document.getElementById("keranjangList");

let total = 0;

keranjang.forEach((item,index)=>{

    total += item.harga;

    list.innerHTML += `
        <div class="keranjang-card">

            <i class="bi bi-card-image produk-icon"></i>

            <div class="keranjang-info">

                <h5>${item.nama}</h5>

                <p>Rp ${item.harga}/Kg</p>

            </div>

            <button
                class="btn btn-danger"
                onclick="hapusProduk(${index})">

                Hapus

            </button>

        </div>
    `;
});

document.getElementById("totalHarga")
.innerHTML =
"Rp " + total.toLocaleString();

function hapusProduk(index){

    keranjang.splice(index,1);

    localStorage.setItem(
        "keranjang",
        JSON.stringify(keranjang)
    );

    location.reload();
}
</script>
</body>
</html>