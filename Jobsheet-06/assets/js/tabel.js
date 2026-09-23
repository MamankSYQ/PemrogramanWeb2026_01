async function muatTabel(fileJson, kunci) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody || !loading) return;

    loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        // simulasi delay jaringan (Latihan 3)
        await new Promise((resolve) => setTimeout(resolve, 3000));

        const res = await fetch("../data/" + fileJson);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const data = await res.json();

        data.forEach(function (item) {
            const tr = document.createElement("tr");

            kunci.forEach(function (k) {
                const td = document.createElement("td");
                td.textContent = item[k];
                tr.appendChild(td);
            });

            const tdAksi = document.createElement("td");
            tdAksi.innerHTML =
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>";
            tr.appendChild(tdAksi);

            tbody.appendChild(tr);
        });
    } catch (err) {
        const tr = document.createElement("tr");
        const td = document.createElement("td");
        td.colSpan = kunci.length + 1; // +1 untuk kolom Aksi
        td.textContent = "Gagal memuat data: " + err.message;
        tr.appendChild(td);
        tbody.appendChild(tr);
    } finally {
        loading.style.display = "none";
    }
}