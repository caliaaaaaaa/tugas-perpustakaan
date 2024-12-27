<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

// Mendapatkan data user berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    header("Location: manage_users.php");
    exit();
}

// Update data user
if (isset($_POST['update_user'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "UPDATE users SET nama = ?, username = ?, password = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama, $username, $password, $role, $id);
    } else {
        $sql = "UPDATE users SET nama = ?, username = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama, $username, $role, $id);
    }

    $stmt->execute();
    header("Location: manage_users.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffe6f0;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #d63384;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #d63384;
            border-radius: 5px;
        }
        input:focus, select:focus, button:focus {
            outline: none;
            border-color: #ff85a2;
        }
        button {
            background-color: #d63384;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff85a2;
        }
        a {
            display: block;
            text-align: center;
            color: #d63384;
            text-decoration: none;
            margin-top: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Pengguna</h1>
        <form method="POST">
            <input type="text" name="nama" value="<?php echo $user['nama']; ?>" placeholder="Nama Lengkap" required>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password Baru (Kosongkan jika tidak ingin diubah)">
            <select name="role" required>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
            </select>
            <button type="submit" name="update_user">Update Pengguna</button>
        </form>
        <a href="manage_users.php">Kembali ke Daftar Pengguna</a>
    </div>
</body>
</html>
