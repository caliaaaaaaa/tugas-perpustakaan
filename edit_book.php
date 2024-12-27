<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Mendapatkan data buku berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();
} else {
    header("Location: manage_books.php");
    exit();
}

// Update data buku
if (isset($_POST['update_book'])) {
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];

    if (!empty($_FILES['cover']['name'])) {
        $cover_name = $_FILES['cover']['name'];
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_path = "uploads/" . $cover_name;

        move_uploaded_file($cover_tmp, $cover_path);
        $sql = "UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?, tahun = ?, cover = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $judul, $pengarang, $penerbit, $tahun, $cover_name, $id);
    } else {
        $sql = "UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?, tahun = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $judul, $pengarang, $penerbit, $tahun, $id);
    }

    $stmt->execute();
    header("Location: manage_books.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <style>
        /* Gaya Umum */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffe6f2; /* Latar belakang pink lembut */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            color: #ff66a3;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #ff66a3;
            text-align: center;
        }

        a {
            color: #ff66a3;
            text-decoration: none;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }

        a:hover {
            color: #ff3385;
            background-color: #ffe6f2;
        }

        /* Formulir */
        form {
            background-color: #ffb3d9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        form input, form button {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ff66a3;
            font-size: 1em;
            background-color: #fff;
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

        form input[type="file"] {
            border: none;
            padding: 0;
        }

        .cover-preview {
            margin-top: 10px;
            text-align: center;
        }

        .cover-preview img {
            max-width: 150px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Edit Buku</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="judul">Judul Buku</label>
        <input type="text" name="judul" id="judul" value="<?php echo $buku['judul']; ?>" placeholder="Judul Buku" required><br>

        <label for="pengarang">Pengarang Buku</label>
        <input type="text" name="pengarang" id="pengarang" value="<?php echo $buku['pengarang']; ?>" placeholder="Pengarang Buku" required><br>

        <label for="penerbit">Penerbit Buku</label>
        <input type="text" name="penerbit" id="penerbit" value="<?php echo $buku['penerbit']; ?>" placeholder="Penerbit Buku" required><br>

        <label for="tahun">Tahun Terbit</label>
        <input type="number" name="tahun" id="tahun" value="<?php echo $buku['tahun']; ?>" placeholder="Tahun Terbit" required><br>

        <label for="cover">Cover Buku</label>
        <input type="file" name="cover" id="cover"><br>

        <div class="cover-preview">
            <p>Cover saat ini:</p>
            <img src="uploads/<?php echo $buku['cover']; ?>" alt="Cover">
        </div>

        <button type="submit" name="update_book">Update Buku</button>
    </form>

    <a href="manage_books.php">Kembali ke Daftar Buku</a>
</body>
</html>
