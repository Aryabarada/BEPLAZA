document.addEventListener('DOMContentLoaded', function() {
    fetchData();
});

function fetchData() {
    const url = 'http://localhost/PlazaBarbershop/API/api.php/booking';

    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const tableBody = document.querySelector('#bookingTable tbody');
        tableBody.innerHTML = ''; // Clear table body

        data.forEach((row, index) => {
            // Check if service_count exists and its value
            if (row.hasOwnProperty('service_count')) {
                // Skip rows with service_count > 0
                if (row.service_count > 0) return;

                // For rows with service_count = 0, display data without service details
                const newRow = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.nama_booking}</td>
                        <td>${row.nomerhp_booking}</td>
                        <td>${row.waktu_booking}</td>
                        <td>${row.tanggal_booking}</td>
                        <td>${row.pesan_booking}</td>
                        <td>
                            <a href="editform.php?id=${row.id_booking}" class="btn btn-success" role="button">Apply</a>
                            <a href="delete.php?id=${row.id_booking}" class="btn btn-danger" role="button">Delete</a>
                        </td>
                    </tr>`;
                tableBody.innerHTML += newRow;
            } else {
                // For rows without service_count, display data with service details
                const serviceNames = row.service_names.join(', ');
                const newRow = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.nama_booking}</td>
                        <td>${row.nomerhp_booking}</td>
                        <td>${serviceNames}</td>
                        <td>${row.waktu_booking}</td>
                        <td>${row.tanggal_booking}</td>
                        <td>${row.pesan_booking}</td>
                        <td>${row.harga_booking}</td>
                        <td>
                            <a href="editform.php?id=${row.id_booking}" class="btn btn-success" role="button">Edit</a>
                            <a href="delete.php?id=${row.id_booking}" class="btn btn-danger" role="button">Delete</a>
                        </td>
                    </tr>`;
                tableBody.innerHTML += newRow;
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
