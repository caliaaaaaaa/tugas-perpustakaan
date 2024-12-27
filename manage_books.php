<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Tambah Buku
if (isset($_POST['add_book'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];

    $cover_name = $_FILES['cover']['name'];
    $cover_tmp = $_FILES['cover']['tmp_name'];
    $cover_path = "uploads/" . $cover_name;

    move_uploaded_file($cover_tmp, $cover_path);

    $sql = "INSERT INTO buku (judul, pengarang, penerbit, tahun, cover) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $judul, $pengarang, $penerbit, $tahun, $cover_name);
    $stmt->execute();

    header("Location: manage_books.php");
}

// Hapus Buku
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_books.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku</title>
    <style>
        /* Gaya umum */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffe6f2; /* Latar belakang pink lembut */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1, h2 {
            color: #ff66a3;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        a {
            color: #ff66a3;
            text-decoration: none;
        }

        a:hover {
            color: #ff3385;
        }

        /* Formulir */
        form {
            background-color: #ffb3d9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            width: 100%;
            max-width: 400px;
        }

        form input, form button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ff66a3;
            font-size: 1em;
        }

        form button {
            background-color: #ff66a3;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #ff3385;
        }

        /* Tabel */
        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ffb3d9;
        }

        table th {
            background-color: #ffb3d9;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #ffe6f2;
        }

        table img {
            max-width: 100px;
        }

        .back {
            background-color: #ff66a3;
            color: white;
            text-align: center;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
        }

        .back:hover {
            background-color: #ff3385;
        }
    </style>
</head>
<body>
    <h1>Kelola Buku</h1>

    <!-- Form Tambah Buku -->
    <h2>Tambah Buku</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="judul" placeholder="Judul Buku" required>
        <input type="text" name="pengarang" placeholder="Pengarang Buku" required>
        <input type="text" name="penerbit" placeholder="Penerbit Buku" required>
        <input type="number" name="tahun" placeholder="Tahun Terbit" required>
        <input type="file" name="cover" required>
        <button type="submit" name="add_book">Tambah Buku</button>
    </form>

    <!-- Daftar Buku -->
    <h2>Daftar Buku</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Cover</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM buku";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):
            $no = 1; // Mulai dari nomor 1
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['judul']; ?></td>
            <td><?php echo $row['pengarang']; ?></td>
            <td><?php echo $row['penerbit']; ?></td>
            <td><?php echo $row['tahun']; ?></td>
            <td><img src="uploads/<?php echo $row['cover']; ?>" alt="Cover"></td>
            <td>
                <a href="edit_book.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="manage_books.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
            <td colspan="7" style="text-align:center;">Tidak ada buku.</td>
        </tr>
        <?php endif; ?>
    </table>

    <a href="admin_dashboard.php" class="back">Kembali ke Dashboard</a>
</body>
</html>
