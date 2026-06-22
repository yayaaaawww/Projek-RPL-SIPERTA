<!DOCTYPE html>
<html lang="en">
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

        <!-- KONTEN -->
        <div class="dashboard-content">

            <h2 class="page-title">Checkout Pemesanan</h2>
            <div class="checkout-layout">
                <div class="checkout-card">

                <h5>Total Harga</h5>

                <p id="namaPesanan"></p>

                <p id="hargaPesanan"></p>

                <p id="stokPesanan"></p>

                <hr>

                <div class="pesanan-item">
                    <p><strong>Padi Premium</strong></p>
                    <p>5 Kg x Rp 6.000</p>
                </div>

                <div class="pesanan-item">
                    <p><strong>Jagung</strong></p>
                    <p>2 Kg x Rp 5.000</p>
                </div>

                <hr>

                <h4>Rp 40.000</h4>

            </div>

            <div class="payment-card">

                <h6>Transfer Bank</h6>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bayar">
                    <label class="form-check-label">Gopay</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bayar">
                    <label class="form-check-label">OVO</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bayar">
                    <label class="form-check-label">DANA</label>
                </div>

                <button class="btn-bayar" onclick="simpanTransaksi()">
                    Kirim Pembayaran
                </button>

            </div>
            </div>
        </div>
        
    </div>
</div>

<script>

document.getElementById("namaPesanan").innerHTML =
localStorage.getItem("namaProduk");

document.getElementById("hargaPesanan").innerHTML =
"Rp " + localStorage.getItem("hargaProduk") + "/Kg";

document.getElementById("stokPesanan").innerHTML =
"Stok : " + localStorage.getItem("stokProduk") + " Ton";

</script>
<script>

function simpanTransaksi()
{
    let transaksi =
    JSON.parse(
        localStorage.getItem("transaksi")
    ) || [];

    transaksi.push({

        tanggal :
        new Date().toLocaleDateString(),

        produk :
        localStorage.getItem("namaProduk"),

        pembeli :
        "Pedagang",

        penjual :
        "Petani",

        total :
        localStorage.getItem("hargaProduk"),

        status :
        "Diproses"

    });

    localStorage.setItem(
        "transaksi",
        JSON.stringify(transaksi)
    );

    alert("Pembayaran berhasil");

    window.location.href =
    "katalogP.html";
}

</script>
</body>
</html>