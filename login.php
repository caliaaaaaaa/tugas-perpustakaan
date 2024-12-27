<?php
session_start();
require 'config.php'; // File konfigurasi koneksi database

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'user') {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Gaya Umum */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fbe3f4; /* Latar belakang pink pastel */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            color: #ff66a3;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-family: 'Comic Sans MS', sans-serif;
            text-align: center;
        }

        .login-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container .input-field {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ff66a3;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #ff66a3;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .login-container button:hover {
            background-color: #ff3385;
            transform: scale(1.05);
        }

        .login-container button:active {
            background-color: #e60073;
        }

        .error-message {
            color: red;
            font-size: 1rem;
            text-align: center;
            margin-top: 10px;
        }

        .icon {
            font-size: 3rem;
            color: #ff66a3;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Icon Lucu -->
        <i class="fas fa-user-circle icon"></i>

        <h1>Login</h1>
        <form method="POST" action="">
            <input type="text" class="input-field" name="username" placeholder="Username" required><br>
            <input type="password" class="input-field" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>

</body>
</html>
