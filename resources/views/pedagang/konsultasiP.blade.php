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

            <h1 class="title">
                SIPERTA
            </h1>

            <div class="profile">
                <i class="bi bi-person-circle"></i>
                <span>Profile</span>
            </div>

        </div>

        <div class="dashboard-content">

    <h2 class="page-title">Konsultasi</h2>

    <div class="chat-layout">

        <!-- LIST KONSULTASI -->

        <div class="chat-list">

            <div class="chat-item active">

                <i class="bi bi-image"></i>

                <div class="chat-info">
                    <div class="chat-title">
                        Konsultasi Padi
                    </div>

                    <small>
                        Bagaimana mengatasi hama...
                    </small>
                </div>

                <button class="btn btn-sm btn-success">
                    Chat
                </button>

            </div>

            <div class="chat-item">
                <i class="bi bi-image"></i>

                <div class="chat-info">
                    <div class="chat-title">
                        Konsultasi Jagung
                    </div>

                    <small>
                        Daun menguning...
                    </small>
                </div>

                <button class="btn btn-sm btn-success">
                    Chat
                </button>

            </div>

            <div class="chat-item">
                <i class="bi bi-image"></i>

                <div class="chat-info">
                    <div class="chat-title">
                        Konsultasi Cabai
                    </div>

                    <small>
                        Hama kutu daun...
                    </small>
                </div>

                <button class="btn btn-sm btn-success">
                    Chat
                </button>

            </div>

        </div>

        <!-- AREA CHAT -->

        <div class="chat-box">

            <div class="chat-header">

                <i class="bi bi-person-circle"></i>

                <strong>
                    Profile Ahli
                </strong>

            </div>

            <div class="chat-body">

                <div class="message received">
                    Selamat siang, ada yang bisa saya bantu?
                </div>

                <div class="message sent">
                    Tanaman padi saya terkena hama wereng.
                </div>

                <div class="message received">
                    Bisa dikirimkan foto tanamannya?
                </div>

            </div>

            <div class="chat-input">

                <input
                    type="text"
                    class="form-control"
                    placeholder="Ketik pesan">

                <button class="btn btn-primary">
                    <i class="bi bi-send-fill"></i>
                </button>

            </div>

        </div>

    </div>

</div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        const form =
        document.getElementById("formKonsultasi");

        const list =
        document.getElementById("consultationList");

        form.addEventListener("submit", function(e){

            e.preventDefault();

            const pertanyaan =
            document.getElementById("pertanyaan").value;

            const card =
            document.createElement("div");

            card.classList.add("consultation-card");

            card.innerHTML = `
                <h5>${pertanyaan}</h5>

                <span class="badge bg-warning text-dark">
                    Menunggu
                </span>
            `;

            list.prepend(card);

            form.reset();

            bootstrap.Modal
            .getInstance(
                document.getElementById("modalKonsultasi")
            )
            .hide();

        });

    </script>

</body>
</html>