<?php
session_start();
include('db.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['mark_complete'])) {
    $assignment_id = $_POST['assignment_id'];
    $new_status = $_POST['status'];
    $query = "UPDATE assignments SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $assignment_id);
    $stmt->execute();
}

$searchKeyword = '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if (isset($_POST['search'])) {
    $searchKeyword = $conn->real_escape_string($_POST['search_keyword']);
}

$query = "SELECT * FROM assignments";

if ($filter == 'completed') {
    $query .= " WHERE status = 'completed'";
} elseif ($filter == 'unfinished') {
    $query .= " WHERE status = 'unfinished'";
}

if (!empty($searchKeyword)) {
    $query .= (strpos($query, 'WHERE') !== false ? ' AND' : ' WHERE') . " assignment LIKE '%$searchKeyword%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-serif">
    <nav class="flex justify-center p-4 bg-white shadow-md">
        <ul class="flex space-x-6">
            <li><a class="text-black" href="dashboard.php">Dashboard</a></li>
            <li><a class="text-black font-semibold" href="assignment.php">Assignment</a></li>
            <li><a class="text-black" href="profile.php">Profile</a></li>
            <li><a class="bg-red-500 text-white px-4 py-2 rounded" href="logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <div class="container mx-auto max-w-4xl bg-white p-6 rounded-lg shadow-md mt-6">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p>Selamat datang di Assignment:</p>

        <div class="flex justify-between items-center my-4">
    <div class="space-x-2"> 
        <a href="dashboard.php?filter=all" class="bg-blue-500 text-white px-4 py-2 rounded-full">All</a>
        <a href="dashboard.php?filter=completed" class="bg-green-500 text-white px-4 py-2 rounded-full">Completed</a>
        <a href="dashboard.php?filter=unfinished" class="bg-yellow-500 text-white px-4 py-2 rounded-full">Unfinished</a>
    </div>
    <form method="post" class="flex items-center"> 
        <input type="text" name="search_keyword" value="<?php echo htmlspecialchars($searchKeyword); ?>" placeholder="Search assignments..." class="border border-gray-300 p-2 rounded-full w-3/4"> 
        <button type="submit" name="search" class="bg-blue-500 text-white px-4 py-2 rounded-full ml-2">Search</button>
    </form>


</div>

        <form method="post">
            <table class="min-w-full border-collapse border border-gray-200 mt-4">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">NIM</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Assignment</th>
                        <th class="border border-gray-300 px-4 py-2">Deadline</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Checkmark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['nim']) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['assignment']) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['deadline']) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td class='border border-gray-300 px-4 py-2'>";
                            echo "<form method='post' action='dashboard.php'>";
                            echo "<input type='hidden' name='assignment_id' value='" . $row['id'] . "'>";
                            if ($row['status'] == 'unfinished') {
                                echo "<input type='hidden' name='status' value='completed'>";
                                echo "<button type='submit' name='mark_complete' class='bg-green-500 text-white px-2 py-1 rounded'>Mark as Complete</button>";
                            } else {
                                echo "<input type='hidden' name='status' value='unfinished'>";
                                echo "<button type='submit' name='mark_complete' class='bg-yellow-500 text-white px-2 py-1 rounded'>Undo Assignments</button>";
                            }
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center border border-gray-300 px-4 py-2'>No assignments found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>
