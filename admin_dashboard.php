<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Gaya dasar */
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffe6f2; /* Pink lembut */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Judul halaman */
        h1 {
            color: #ff66a3; /* Pink cerah */
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        /* Kontainer menu */
        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            background-color: #ffb3d9; /* Pink lebih cerah */
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Tombol menu */
        .menu a {
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            background-color: #ff66a3; /* Pink cerah */
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }

        /* Efek hover pada tombol */
        .menu a:hover {
            background-color: #ff3385; /* Pink lebih gelap */
            transform: scale(1.1);
        }

        /* Efek klik pada tombol */
        .menu a:active {
            background-color: #cc295f; /* Pink lebih gelap lagi */
        }

        /* Tombol logout */
        .menu a.logout {
            background-color: #ff4d94; /* Pink spesial untuk logout */
        }

        .menu a.logout:hover {
            background-color: #e6397a; /* Pink gelap spesial untuk logout */
        }
    </style>
</head>
<body>
    <h1>Selamat Datang, Admin</h1>
    <div class="menu">
        <a href="manage_books.php">Kelola Buku</a>
        <a href="manage_users.php">Kelola Pengguna</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>
