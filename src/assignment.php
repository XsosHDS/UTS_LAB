<?php
session_start();
include('db.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}

$editAssignment = [];

if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);  // Hapus setelah ditampilkan
} else {
    $successMessage = '';
}

if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']);  // Hapus setelah ditampilkan
} else {
    $errorMessage = '';
}

if (isset($_GET['edit'])) {
    $assignment_id = $_GET['edit'];
    $query = "SELECT * FROM assignments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editAssignment = $result->fetch_assoc();
    } else {
        $_SESSION['errorMessage'] = "Assignment not found.";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $name = $_POST['name'];
    $assignment = $_POST['assignment'];
    $deadline = $_POST['deadline'];
    
    if (isset($_POST['assignment_id'])) {
        $assignment_id = $_POST['assignment_id'];
        $query = "UPDATE assignments SET nim = ?, name = ?, assignment = ?, deadline = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nim, $name, $assignment, $deadline, $assignment_id);
    } else {
        $query = "INSERT INTO assignments (nim, name, assignment, deadline) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nim, $name, $assignment, $deadline);
    }

    if ($stmt->execute()) {
        $_SESSION['successMessage'] = isset($_POST['assignment_id']) ? "Assignment updated successfully!" : "Assignment added successfully!";
    } else {
        $_SESSION['errorMessage'] = "Failed to save assignment. Please try again.";
    }
    $stmt->close();

    header('Location: assignment.php');  
    exit();
}

if (isset($_GET['delete'])) {
    $assignment_id = $_GET['delete'];
    $query = "DELETE FROM assignments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $assignment_id);
    if ($stmt->execute()) {
        $_SESSION['successMessage'] = "Assignment deleted successfully!";
    } else {
        $_SESSION['errorMessage'] = "Failed to delete assignment. Please try again.";
    }
    $stmt->close();

    header('Location: assignment.php');
    exit();
}

$query = "SELECT * FROM assignments";
$result = $conn->query($query);
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment</title>
    <link href="../css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-serif">
<nav class="flex justify-center p-4 bg-white shadow-md">
    <ul class="flex space-x-6">
        <li><a class="text-black" href="dashboard.php">Dashboard</a></li>
        <li><a class="text-black font-semibold" href="assignment.php">Assignment</a></li>
        <li><a class="text-black" href="profile.php">Profile</a></li>
        <li><a class="bg-orange-300 text-white px-4 py-2 rounded" href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container mx-auto max-w-3xl bg-white p-6 rounded-lg shadow-md mt-6">
    <h2 class="text-2xl font-bold mb-6 text-center">Assignment Form</h2>

    <?php if (!empty($successMessage)): ?>
        <div id="alert" class="bg-green-200 text-green-800 p-4 rounded absolute top-5 right-5 shadow-lg z-50">
            <?= $successMessage ?>
        </div>
    <?php elseif (!empty($errorMessage)): ?>
        <div id="alert" class="bg-red-200 text-red-800 p-4 rounded absolute top-5 right-5 shadow-lg z-50">
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <form action="assignment.php" method="POST" class="mb-6">
        <div class="mb-4">
            <label for="nim" class="block mb-2">NIM</label>
            <input type="text" id="nim" name="nim" placeholder="Enter NIM" value="<?= isset($_GET['edit']) ? htmlspecialchars($editAssignment['nim']) : '' ?>" required class="border border-gray-300 p-2 w-full rounded">
        </div>
        <div class="mb-4">
            <label for="name" class="block mb-2">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter Name" value="<?= isset($_GET['edit']) ? htmlspecialchars($editAssignment['name']) : '' ?>" required class="border border-gray-300 p-2 w-full rounded">
        </div>
        <div class="mb-4">
            <label for="assignment" class="block mb-2">Assignment</label>
            <input type="text" id="assignment" name="assignment" placeholder="Enter Assignment" value="<?= isset($_GET['edit']) ? htmlspecialchars($editAssignment['assignment']) : '' ?>" required class="border border-gray-300 p-2 w-full rounded">
        </div>
        <div class="mb-4">
            <label for="deadline" class="block mb-2">Deadline</label>
            <input type="date" id="deadline" name="deadline" value="<?= isset($_GET['edit']) ? htmlspecialchars($editAssignment['deadline']) : '' ?>" required class="border border-gray-300 p-2 w-full rounded">
        </div>
        <?php if (isset($_GET['edit'])): ?>
            <input type="hidden" name="assignment_id" value="<?= $_GET['edit'] ?>">
        <?php endif; ?>

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-full">
                <?= isset($_GET['edit']) ? 'Update Assignment' : 'Submit' ?>
            </button>
        </div>
    </form>

    <h3 class="text-xl font-bold mt-6">Assignments</h3>
    <table class="min-w-full border-collapse border border-gray-200 mt-4">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">NIM</th>
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Assignment</th>
                <th class="border border-gray-300 px-4 py-2">Deadline</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['nim']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['assignment']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['deadline']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="assignment.php?edit=<?= $row['id'] ?>" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                            <a href="assignment.php?delete=<?= $row['id'] ?>" class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center border border-gray-300 px-4 py-2">No assignments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    setTimeout(function() {
        var alert = document.getElementById('alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 2000);
</script>

</body>
</html>

