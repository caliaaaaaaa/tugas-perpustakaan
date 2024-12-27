<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Tambah User
if (isset($_POST['add_user'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nama, $username, $password, $role);
    $stmt->execute();

    header("Location: manage_users.php");
}

// Hapus User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_users.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <style>
        /* Gaya Umum */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fbe3f4; /* Background pink pastel */
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

        /* Form Tambah Pengguna */
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            width: 80%;
            max-width: 500px;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        form button {
            background-color: #ff66a3;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #ff3385;
        }

        /* Tabel Daftar Pengguna */
        table {
            width: 80%;
            max-width: 800px;
            border-collapse: collapse;
            margin-top: 40px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
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

        a {
            color: #ff66a3;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Link Kembali */
        .back-link {
            margin-top: 30px;
            font-size: 1rem;
            color: #ff66a3;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Kelola Pengguna</h1>

    <!-- Form Tambah Pengguna -->
    <h2>Tambah Pengguna</h2>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required><br>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br>
        <button type="submit" name="add_user">Tambah Pengguna</button>
    </form>

    <!-- Daftar Pengguna -->
    <h2>Daftar Pengguna</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):
            $no = 1;
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="manage_users.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
        <tr>
            <td colspan="5">Tidak ada pengguna.</td>
        </tr>
        <?php endif; ?>
    </table>

    <a href="admin_dashboard.php" class="back-link">Kembali ke Dashboard</a>
</body>
</html>
