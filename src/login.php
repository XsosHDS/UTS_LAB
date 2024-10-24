<?php
session_start();
include('db.php');
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit();
}
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $email, $db_password);
                $stmt->fetch();
                if (password_verify($password, $db_password)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id']       = $id;
                    $_SESSION['username'] = $db_username;
                    $_SESSION['email']    = $email;
                    header('Location: dashboard.php');
                    exit();
                } 
                else {
                    $error = "Invalid username or password!";
                }
            } 
            else {
                $error = "Invalid username or password!";
            }
            $stmt->close();
        } 
        else {
            $error = "Something went wrong. Please try again later.";
        }
    } 
    else {
        $error = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link href="../css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-serif"> 
    <div class="flex items-center justify-center min-h-screen">
        <div class="max-w-sm w-full p-8 bg-white rounded-lg shadow-md">
            <div class="flex justify-center mb-6">
                <img src="../images/logo.png" alt="Logo" class="w-24">
            </div>
            <form method="POST" action="login.php">
                <h3 class="text-center text-2xl font-bold mb-6 text-gray-700">Login</h3> 
                <?php if ($error): ?>
                    <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-center">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <div class="mb-4">
                    <label for="username" class="block text-left mb-2 font-medium text-gray-600">Username</label> 
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-300" name="username" id="username" required
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-left mb-2 font-medium text-gray-600">Password</label> 
                    <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-300" name="password" id="password" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition duration-200">Login</button>
                <p class="text-center mt-4 text-gray-600">Tidak Punya Akun? <a href="register.php" class="text-blue-500 hover:underline">Register here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
