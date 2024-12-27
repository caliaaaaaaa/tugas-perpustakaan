<?php
// Pengaturan database
$host = 'localhost';        // Alamat server database
$user = 'root';             // Nama pengguna database
$password = '';             // Password database (kosong jika tidak ada password)
$database = 'perpustakaan'; // Nama database yang digunakan

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
    // Koneksi berhasil, bisa melanjutkan proses selanjutnya
    // echo "Koneksi berhasil!";
}
?>
