document.addEventListener("DOMContentLoaded", function() {
    var WatchData = [
        "08:00:00",
        "08:30:00",
        "09:00:00",
        "09:30:00",
        "10:00:00",
        "10:30:00",
        "11:00:00",
        "11:30:00",
        "12:00:00",
        "12:30:00",
        "13:00:00",
        "13:30:00",
        "14:00:00",
        "14:30:00",
        "15:00:00",
        "15:30:00",
        "16:00:00"
    ];

    function validateDateTime() {
        var tanggal = document.getElementById("tanggal").value;
        var waktuSelect = document.getElementById("waktu");

        if (tanggal === "") {
            waktuSelect.innerHTML = "<option value=''>Pilih Tanggal Terlebih Dahulu</option>";
            waktuSelect.disabled = true;
        } else {
            waktuSelect.disabled = false;
            var options = "<option value=''>Pilih Waktu</option>";
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../layout/function.php?tanggal=" + tanggal, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var uniqueTimes = [...new Set(response)];
                    console.log(WatchData);
                    for (var i = 0; i < WatchData.length; i++) {
                        // Periksa apakah data[i] tidak ada dalam array uniqueTimes
                        if (!uniqueTimes.includes(WatchData[i])) {
                            options += "<option value='" + WatchData[i] + "'>" + WatchData[i] + "</option>";
                            console.log(options);
                        }
                    }
                    waktuSelect.innerHTML = options; // Menetapkan opsi waktu ke dalam elemen select
                }
            };
            xhr.send();
        }
    }
    document.getElementById("tanggal").addEventListener("change", validateDateTime);
});
