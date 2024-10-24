<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: src/dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-300 font-serif">
    <div class="container mx-auto p-4">
        <!-- Header -->
        <header class="flex items-center justify-between p-4 bg-white shadow-md rounded-lg">
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold">TO-DO List</h1>
            </div>
            <div class="flex items-center space-x-10">
                <a href="src/login.php" class="text-lg font-semibold text-blue-600 hover:text-blue-800 transition">Login</a>
                <a href="src/register.php" class="text-lg font-semibold text-blue-600 hover:text-blue-800 transition">Register</a>
            </div>
        </header>
        <main class="mt-10 text-center">
            <h1 class="text-5xl font-bold mb-4 text-gray-800">WEB PROGRAMMING</h1>
            <p class="text-lg text-gray-600">SELAMAT DATANG DI ASSIGNMENT SCHEDULING.</p>
            <div class="mt-6 flex justify-center space-x-4">
                <a href="src/login.php" class="bg-pink-500 text-white px-6 py-3 rounded-full shadow-md hover:bg-pink-600 transition duration-200">Login</a>
                <a href="src/register.php" class="bg-green-500 text-white px-6 py-3 rounded-full shadow-md hover:bg-green-600 transition duration-200">Register</a>
            </div>
        </main>
        <!-- Footer -->
        <footer class="mt-10 text-center">
            <p class="text-sm text-gray-500">© 2024 My Website. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
