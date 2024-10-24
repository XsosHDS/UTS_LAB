<?php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<p style='color:red; text-align:center;'>Username or Email already exists!</p>";
    } 
    else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Registration successful! You can now <a href='login.php'>login</a>.</p>";
        } 
        else {
            echo "<p style='color:red; text-align:center;'>Registration failed. Please try again.</p>";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
    <link href="../css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-serif">
    <div class="flex items-center justify-center min-h-screen">
        <div class="max-w-md w-full p-8 bg-white rounded-lg shadow-md">
            <div class="text-center mb-6">
                <img src="../images/logo.png" alt="Logo" class="w-24 mx-auto mb-4">
                <h3 class="text-2xl font-bold">REGISTER</h3>
            </div>
            <form method="POST" action="register.php">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 mb-2">Username</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" name="email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 mb-2">Password</label>
                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" name="password" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition duration-200">Register</button>
                <p class="text-center mt-4 text-gray-600">Sudah Memiliki Akun? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
