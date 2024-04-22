<?php
include('config.php');

session_start();

// Check if user is not logged in or not a manager, redirect to login page
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch owners from the database
$sql = "SELECT * FROM owners";
$result = mysqli_query($conn, $sql);

// Process delete request
if (isset($_POST['delete']) && isset($_POST['owner_id'])) {
    $owner_id = $_POST['owner_id'];
    $delete_sql = "DELETE FROM owners WHERE ID = '$owner_id'";
    mysqli_query($conn, $delete_sql);
    header("Location: view_owners.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Owners</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <!-- Manage Owners Section -->
    <div class="container mt-5">
        <h1>Manage Owners</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Information</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>" . $row['Contact_Info'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>";
                        echo "<a href='edit_owner.php?id=" . $row['ID'] . "' class='btn btn-primary mr-2'>Edit</a>";
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' style='display:inline;'>";
                        echo "<input type='hidden' name='owner_id' value='" . $row['ID'] . "'>";
                        echo "<input type='submit' name='delete' value='Delete' class='btn btn-danger'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No owners found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
