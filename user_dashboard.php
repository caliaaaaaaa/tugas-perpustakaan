<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Ambil data buku
$sql = "SELECT * FROM buku";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* Gaya Umum */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fbe3f4; /* Latar belakang pink pastel */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            padding-top: 50px;
        }

        h1 {
            color: #ff66a3;
            font-size: 3rem;
            margin-bottom: 40px;
            font-family: 'Comic Sans MS', sans-serif;
        }

        h2 {
            color: #ff66a3;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        /* Tabel Daftar Buku */
        table {
            width: 80%;
            max-width: 800px;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: #ff66a3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ffebf2;
        }

        img {
            border-radius: 5px;
        }

        /* Link Logout */
        .logout-link {
            margin-top: 30px;
            font-size: 1rem;
            color: #ff66a3;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Selamat Datang, <?php echo $_SESSION['user']['nama']; ?></h1>
    <h2>Daftar Buku</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun Terbit</th>
            <th>Cover</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['pengarang']; ?></td>
                    <td><?php echo $row['penerbit']; ?></td>
                    <td><?php echo $row['tahun']; ?></td>
                    <td><img src="uploads/<?php echo $row['cover']; ?>" alt="Cover Buku" width="100"></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Tidak ada buku.</td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="logout.php" class="logout-link">Logout</a>
</body>
</html>
