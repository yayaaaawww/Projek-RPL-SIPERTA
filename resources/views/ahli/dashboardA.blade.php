<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Ambil data riwayat konsultasi dari API Laravel kamu
        // Ganti '/api/ahli/konsultasi' dengan endpoint asli yang kamu tes di Thunder Client kemarin
        fetch('/api/ahli/konsultasi') 
            .then(response => response.json())
            .then(data => {
                // Cari elemen tabel atau list tempat riwayat di HTML temenmu
                // (Misal temenmu pake tag <tbody> dengan id atau class tertentu)
                const tabelRiwayat = document.querySelector('tbody'); 
                
                // 2. Jika di database kamu belum ada data (kosong), kosongkan tampilan figma tadi
                if (data.length === 0) {
                    tabelRiwayat.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Belum ada riwayat konsultasi.
                            </td>
                        </tr>`;
                    return;
                }

                // 3. Tapi kalau ada datanya, hapus data palsu dari figma, ganti dengan data asli
                tabelRiwayat.innerHTML = ''; // Kosongkan data figma
                
                data.forEach(row => {
                    tabelRiwayat.innerHTML += `
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="detailKonsultasi(${row.id})">
                            <td class="py-3 px-4">${row.nama_petani}</td>
                            <td class="py-3 px-4">${row.topik}</td>
                            <td class="py-3 px-4">${row.status}</td>
                            <td class="py-3 px-4">${row.tanggal}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => console.error("Waduh eror ambil riwayat:", error));
    });

    // 4. Fungsi pas diklik biar muncul detailnya (bisa dialihkan ke halaman konsultasi)
    function detailKonsultasi(id) {
        window.location.href = `konsultasiA.html?id=${id}`;
    }
</script>