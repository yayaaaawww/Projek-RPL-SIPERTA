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

        <div class="dashboard-header">

            <h1 class="title">SIPERTA</h1>

        </div>

        <div class="dashboard-content">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h2 class="page-title">
                    Data Pengguna
                </h2>

                <button
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">

                    Tambah Pengguna

                </button>

            </div>

            <div class="activity-card">

                <table class="table table-hover">

                    <thead>

                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody id="userTable">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5>Tambah Pengguna</h5>

            </div>

            <div class="modal-body">

                <form id="formUser">

                    <input
                        type="text"
                        id="nama"
                        class="form-control mb-3"
                        placeholder="Nama"
                        required>

                    <input
                        type="email"
                        id="email"
                        class="form-control mb-3"
                        placeholder="Email"
                        required>

                    <select
                        id="role"
                        class="form-control mb-3">

                        <option>Petani</option>
                        <option>Pedagang</option>
                        <option>Ahli</option>

                    </select>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Simpan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>

let users =
JSON.parse(localStorage.getItem("users")) || [];

const table =
document.getElementById("userTable");

function tampilkanUser()
{
    table.innerHTML = "";

    users.forEach((user,index)=>{

        table.innerHTML += `
        <tr>

            <td>${user.nama}</td>

            <td>${user.role}</td>

            <td>${user.email}</td>

            <td>
                <span class="badge bg-success">
                    Aktif
                </span>
            </td>

            <td>

                <button
                    class="btn btn-danger btn-sm"
                    onclick="hapusUser(${index})">

                    Hapus

                </button>

            </td>

        </tr>
        `;
    });
}

tampilkanUser();

document
.getElementById("formUser")
.addEventListener("submit", function(e){

    e.preventDefault();

    users.push({

        nama:
        document.getElementById("nama").value,

        email:
        document.getElementById("email").value,

        role:
        document.getElementById("role").value

    });

    localStorage.setItem(
        "users",
        JSON.stringify(users)
    );

    location.reload();

});

function hapusUser(index)
{
    users.splice(index,1);

    localStorage.setItem(
        "users",
        JSON.stringify(users)
    );

    location.reload();
}

</script>

</body>
</html>